<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Department;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Task;
use App\Notifications\TaskAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

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

        return view('panel.pages.tasks.index')
            ->with([
                'tasks' => Task::with(['inquiry', 'taskLists'])
                    ->when($search, fn ($query) => $query->where('name', 'like', "%". $search ."%"))
                    ->latest()
                    ->paginate(10),
                'departments' => Department::get(['id', 'name'])
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

        if(array_key_exists('user', $validated)){
            $task = User::find($validated['user'])->tasks()->create($validated);
            $users = User::find($validated['user']);
            $content = __('translates.tasks.content.user');
        }else{
            $task = Department::find($validated['department'])->tasks()->create($validated);
            $users = User::where('id', '!=' ,auth()->id())->where('department_id', $validated['department'])->get();
            $content = __('translates.tasks.content.department');
        }
        $url = config('app.url') . "/module/tasks/{$task->getAttribute('id')}";

        Notification::send($users, new TaskAssigned($content, $url, 'translates.tasks.new'));

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

        $validated['user_id'] = auth()->id();

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
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
