<?php

namespace App\Http\Controllers\Modules;

use App\Events\TaskCreated;
use App\Events\TaskStatusDone;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Department;
use App\Models\Inquiry;
use App\Models\TaskList;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Task::class, 'task');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $limit  = $request->get('limit', 25);

        $filters = [
            'type' => $request->get('type') ?? (auth()->user()->isDeveloper() ? 1 : (Task::userCanViewAll() ? 3 : 1)),
            'status' => $request->get('status'),
            'priority' => $request->get('priority'),
            'department' => $request->get('department'),
            'user' => $request->get('user'),
            'must_start_at' => $request->get('must_start_at') ?? now()->firstOfMonth()->format('Y/m/d') . ' - ' . now()->format('Y/m/d'),
        ];

        $dateRanges = [
            'must_start_at' => explode(' - ', $filters['must_start_at']),
        ];

        $dateFilters = [
            'must_start_at' => $request->has('check_start_daterange'),
        ];

        $statuses = Task::statuses();
        $priorities = Task::priorities();
        $types = Task::types();

        $user = auth()->user();

        return view('panel.pages.tasks.index')
            ->with([
                'filters' => $filters,
                'tasks' => Task::with('taskable', 'user')->withCount([
                    'taskLists as all_tasks_count',
                    'taskLists as done_tasks_count' => fn (Builder $query) => $query->where('is_checked', true)
                ])
                    ->when($search, fn ($query) => $query->where('name', 'like', "%". $search ."%"))
                    ->where(function ($query)  use ($filters, $user, $dateFilters, $dateRanges) {
                        foreach ($filters as $column => $value) {
                            $query->when($value, function ($query, $value) use ($column, $filters, $user, $dateFilters, $dateRanges) {
                                if ($column == 'department'){
                                    $query->whereHasMorph('taskable', [Department::class, User::class], function($q, $type) use ($user, $value){
                                        if ($type === Department::class) {
                                            $q->where('id', $value);
                                        }else{
                                            $q->where('department_id', $value);
                                        }
                                    });
                                }elseif ($column == 'user'){
                                    $query->whereHasMorph('taskable', [User::class], fn ($q) => $q
                                        ->where('surname', 'like', $value)
                                        ->orWhere('name', 'like', $value));
                                }elseif($column == 'type'){
                                    switch ($value){
                                        case 1:
                                            $query->whereHasMorph('taskable', [Department::class, User::class], function($q, $type) use ($user){
                                                if ($type === Department::class) {
                                                    $q->where('id', $user->getRelationValue('department')->getAttribute('id'));
                                                }else{
                                                    $q->where('id', $user->getAttribute('id'));
                                                }
                                            });
                                            break;
                                        case 2:
                                            $query->where('user_id', $user->getAttribute('id'));
                                            break;
                                        case 3:
                                            if(!Task::userCanViewAll()){
                                                if ($user->hasPermission('department-chief')){
                                                    $query->whereHasMorph('taskable', [Department::class, User::class] , function ($q, $type) use ($user){
                                                        if ($type === Department::class) {
                                                            $q->where('id', $user->getRelationValue('department')->getAttribute('id'));
                                                        }else{
                                                            $q->where('department_id', $user->getRelationValue('department')->getAttribute('id'));
                                                        }
                                                    });
                                                }else{
                                                    $query->whereHasMorph('taskable', [Department::class, User::class] , function ($q, $type) use ($user){
                                                        if ($type === Department::class) {
                                                            $q->where('id', $user->getRelationValue('department')->getAttribute('id'));
                                                        }else{
                                                            $q->where('id', $user->getAttribute('id'));
                                                        }
                                                    });
                                                }
                                                $query->orWhere('user_id', $user->getAttribute('id'));
                                            }
                                            break;
                                    }
                                }else{
                                    if ($column == 'search'){
                                        $query->where($column, 'LIKE', "%$value%");
                                    }
                                    if ($column == 'status' || $column == 'priority'){
                                        $query->where($column, $value);
                                    }
                                    else if (is_numeric($value)){
                                        $query->where($column, $value);
                                    }
                                    else if(is_string($value) && $dateFilters[$column]){
                                        $query->whereBetween($column,
                                            [
                                                Carbon::parse($dateRanges[$column][0])->startOfDay(),
                                                Carbon::parse($dateRanges[$column][1])->endOfDay()
                                            ]
                                        );
                                    }
                                }
                            });
                        }
                    })
                    ->latest()
                    ->paginate($limit),
                'departments' => Department::get(['id', 'name']),
                'statuses' => $statuses,
                'priorities' => $priorities,
                'types' => $types
            ]);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function create()
    {
        return view('panel.pages.tasks.edit')
            ->with([
                'action' => route('tasks.store'),
                'method' => 'POST',
                'data' => null,
                'departments' => Department::pluck('name', 'id')->toArray(),
                'inquiry' => Inquiry::find(request()->get('inquiry_id'))
            ]);
    }

    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        list($validated['must_start_at'], $validated['must_end_at']) = explode(' - ', $validated['task_dates']);

        if($request->has('user')){
            $taskable = User::find($validated['user']);
        } else {
            $taskable = Department::find($validated['department']);
        }

        $task = $taskable->tasks()->create($validated);

        if($request->get('list_id')){
            TaskList::find($request->get('list_id'))->update(['parent_task_id' => $task->id]);
        }

        event(new TaskCreated($task));

        return redirect()
            ->route('tasks.show', $task)
            ->withNotify('success', __('translates.tasks.created', ['name' => $task->getAttribute('name')]), true);
    }

    public function show(Task $task)
    {
        return view('panel.pages.tasks.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $task,
                'departments' => Department::pluck('name', 'id')->toArray()
            ]);
    }

    public function edit(Task $task)
    {
        return view('panel.pages.tasks.edit')
            ->with([
                'action' => route('tasks.update', $task),
                'method' => "PUT",
                'data' => $task,
                'departments' => Department::pluck('name', 'id')->toArray()
            ]);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        list($validated['must_start_at'], $validated['must_end_at']) = explode(' - ', $validated['task_dates']);

        if($request->has('user')){
            $validated['taskable_type'] = User::class;
            $validated['taskable_id']   = $validated['user'];
        }else{
            $validated['taskable_type'] = Department::class;
            $validated['taskable_id']   = $validated['department'];
        }

        if ($task->getAttribute('status') != $validated['status'] && $validated['status'] == 'done'){
            event(new TaskStatusDone($task, auth()->user(), $task->getAttribute('status'), $validated['status']));
        }

        $task->update($validated);

        return redirect()->route('tasks.show', $task)->withNotify('info', $task->getAttribute('name'));
    }

    public function destroy(Task $task)
    {
        if ($task->delete()) {
            return response('OK');
        }

        return response()->setStatusCode('204');
    }
}
