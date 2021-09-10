<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Department;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->get('search');
        $department = $request->get('department');

        return view('panel.pages.tasks.index')
            ->with([
                'tasks' => Task::with(['inquiry', 'department'])
                    ->when($search, fn ($query) => $query->where('name', 'like', "%". $search ."%"))
                    ->when($department, fn($q) => $q->where('department_id', $department))
                    ->paginate(10),
                'departments' => Department::get(['id', 'name'])
            ]);
    }

    public function create()
    {
        return view('panel.pages.tasks.edit')
            ->with([
                'action' => route('tasks.store'),
                'method' => null,
                'data' => null,
                'departments' => Department::pluck('name', 'id')->toArray()
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
            $user = User::find($validated['user']);
            $task = $user->tasksToMe()->create($validated);
        }else{
            $department = Department::find($validated['department']);
            $task = $department->tasks()->create($validated);
        }

        return redirect()
            ->route('tasks.edit', $task)
            ->withNotify('success', $task->getAttribute('name'));
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
        dd($request->validated());
        $task->update($request->validated());

        return back()->withNotify('info', $task->getAttribute('name'));
    }

    public function destroy(Task $task)
    {
        if ($task->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
