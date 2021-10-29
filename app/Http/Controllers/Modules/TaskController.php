<?php

namespace App\Http\Controllers\Modules;

use App\Events\Notification;
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
                        $query->whereHasMorph('taskable', [Department::class, User::class] , function ($q, $type) use ($user_id){
                            if ($type === Department::class) {
                                $q->where('id', auth()->user()->getRelationValue('department')->getAttribute('id'));
                            }else{
                                $q->where('id', $user_id);
                            }
                        });
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
                    ->paginate(10),
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

        $task_dates = explode(' - ', $validated['task_dates']);
        $validated['must_start_at'] = $task_dates[0];
        $validated['must_end_at'] = $task_dates[1];

        //clear task_dates after explode
        unset($validated['task_dates']);

        $validated['user_id'] = auth()->id();

        // notifiable users
        $users = [];
        if(array_key_exists('user', $validated)){
            $task = User::find($validated['user'])->tasks()->create($validated);
            $users[] = User::find($validated['user']);
            $content = __('translates.tasks.content.user');
        }else{
            $task = Department::find($validated['department'])->tasks()->create($validated);
            foreach (User::where('id', '!=' ,auth()->id())->where('department_id', $validated['department'])->get() as $user) {
                $users[] = $user;
            }
            $content = __('translates.tasks.content.department');
        }
        $url = route('tasks.show', $task->getAttribute('id'));

        event(new Notification($request->user(), $users, trans('translates.tasks.new'), $content, $url));

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

        $task_dates = explode(' - ', $validated['task_dates']);
        $validated['must_start_at'] = $task_dates[0];
        $validated['must_end_at'] = $task_dates[1];

        //clear task_dates after explode
        unset($validated['task_dates']);

        if($validated['status'] == 'done'){
            $validated['done_at'] = now();
            $validated['done_by_user_id'] = $validated['user_id'];
        }

        if(array_key_exists('user', $validated)){
            $validated['taskable_type'] = User::class;
            $validated['taskable_id']   = $validated['user'];
        }else{
            $validated['taskable_type'] = Department::class;
            $validated['taskable_id']   = $validated['department'];
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
