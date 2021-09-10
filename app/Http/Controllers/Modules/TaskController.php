<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Department;
use App\Models\Inquiry;
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
        $task = Task::create($request->validated());

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
