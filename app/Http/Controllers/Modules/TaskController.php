<?php

namespace App\Http\Controllers\Modules;

use App\Events\TaskCreated;
use App\Events\TaskStatusUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Department;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

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
            'department' => $request->get('department'),
            'user' => $request->get('user'),
            'status' => $request->get('status'),
            'priority' => $request->get('priority'),
            'type' => $request->get('type') ?? 1
        ];

        $statuses = Task::statuses();
        $priorities = Task::priorities();
        $types = Task::types();

        $user_id = auth()->id();

        return view('panel.pages.tasks.index')
            ->with([
                'tasks' => Task::withCount([
                    'taskLists as all_tasks_count',
                    'taskLists as done_tasks_count' => fn (Builder $query) => $query->where('is_checked', true)
                ])
                    ->when(!Task::userCanViewAll(), function ($query) use ($user_id){
                        if (!auth()->user()->hasPermission('viewDepartment-task')){
                            $query->whereHasMorph('taskable', [Department::class, User::class] , function ($q, $type) use ($user_id){
                                if ($type === Department::class) {
                                    $q->where('id', auth()->user()->getRelationValue('department')->getAttribute('id'));
                                }else{
                                    $q->where('id', $user_id);
                                }
                            });
                        }else{
                            $query->whereHasMorph('taskable', [Department::class, User::class] , function ($q, $type) use ($user_id){
                                if ($type === User::class) {
                                    $q->whereBelongsTo(auth()->user()->getRelationValue('department'));
                                }else{
                                    $q->where('id', auth()->user()->getRelationValue('department')->getAttribute('id'));
                                }
                            });
                        }
                        $query->orWhere('user_id', $user_id);
                    })
                    ->when($search, fn ($query) => $query->where('name', 'like', "%". $search ."%"))
                    ->where(function ($query)  use ($filters, $user_id) {
                        foreach ($filters as $column => $value) {
                            $query->when($value, function ($query, $value) use ($column, $filters, $user_id) {
                                if ($column == 'department'){
                                    $query->whereHasMorph('taskable', [Department::class], fn ($q) => $q->where('id', $value));
                                }elseif ($column == 'user'){
                                    $query->whereHasMorph('taskable', [User::class], fn ($q) => $q
                                        ->where('surname', 'like', $value)
                                        ->orWhere('name', 'like', $value));
                                }elseif($column == 'type'){
                                    switch ($value){
                                        case 1:
                                            $query->whereHasMorph('taskable', [Department::class, User::class], function($q, $type) use ($user_id){
                                                if ($type === Department::class) {
                                                    $q->where('id', auth()->user()->getRelationValue('department')->getAttribute('id'));
                                                }else{
                                                    $q->where('id', $user_id);
                                                }
                                            });
                                            break;
                                        case 2:
                                            $query->where('user_id', $user_id);
                                            break;
                                    }
                                }else{
                                    $query->where($column, $value);
                                }
                            });
                        }
                    })
                    ->latest()
                    ->simplePaginate($limit),
                'departments' => Department::get(['id', 'name']),
                'statuses' => $statuses,
                'priorities' => $priorities,
                'types' => $types
            ]);
    }

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

        $validated['user_id'] = auth()->id();

        if($request->has('user')){
            $taskable = User::find($validated['user']);
        } else {
            $taskable = Department::find($validated['department']);
        }

        $task = $taskable->tasks()->create($validated);

        if($task->inquiry()->exists()){
            $task->getRelationValue('inquiry')
                ->parameters()
                ->updateExistingPivot(Inquiry::STATUS_PARAMETER, ['value' => Inquiry::REDIRECTED]);
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

        if($validated['status'] == 'done'){
            $validated['done_at'] = now();
            $validated['done_by_user_id'] = auth()->id();
            if($task->inquiry()->exists()){
                $task->getRelationValue('inquiry')
                    ->parameters()
                    ->updateExistingPivot(Inquiry::STATUS_PARAMETER, ['value' => Inquiry::DONE]);
            }
        }

        if($request->has('user')){
            $validated['taskable_type'] = User::class;
            $validated['taskable_id']   = $validated['user'];
        }else{
            $validated['taskable_type'] = Department::class;
            $validated['taskable_id']   = $validated['department'];
        }

        if ($task->getAttribute('status') != $validated['status']){
            event(new TaskStatusUpdated($task, auth()->user(), $task->getAttribute('status'), $validated['status']));
        }

        $task->update($validated);

        return redirect()->route('tasks.show', $task)->withNotify('info', $task->getAttribute('name'));
    }

    public function destroy(Task $task)
    {
        if ($task->delete()) {
            foreach (DatabaseNotification::where("data->url", route('tasks.show', $task))->get() as $notification){
                $notification->delete();
            }
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
