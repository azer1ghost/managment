<?php

namespace App\Http\Controllers;

use App\Events\TaskAssigned;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function index()
    {
        abort(404);
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->id();

        $list = TaskList::create($data);
        $task = $list->task;
        if($task->taskable->getTable() == 'users'){
            $users[] = User::find($task->taskable->id);
        }elseif ($task->taskable->getTable() == 'departments'){
            foreach (User::where('id', '!=', $data['user_id'])->where('department_id', $task->taskable->id)->get() as $user) {
                $users[] = $user;
            }
        }

        event(new TaskAssigned($request->user(), $users, trans('translates.tasks.list.new'), $list->name, $request->url));

        return redirect($request->url . '#task-lists-header');
    }

    public function show(TaskList $taskList)
    {
        abort(404);
    }

    public function edit(TaskList $taskList)
    {
        abort(404);
    }

    public function update(Request $request, TaskList $taskList)
    {
        $data = $request->all();

        if(array_key_exists('is_checked', $data)){
            $data['last_checked_by'] = auth()->id();
        }

        unset($data['_method']);
        unset($data['_token']);

        $taskList->update($data);

        return redirect($data['url'] . '#task-lists-header');
    }

    public function destroy(Request $request, TaskList $taskList)
    {
        if ($taskList->delete()) {
            return redirect($request->url . '#task-lists-header');
        }
        return back()->withNotify('error', 'Cannot be deleted');
    }
}
