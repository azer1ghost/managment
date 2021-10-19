<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
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

        TaskList::create($data);

        return back();
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
            $data['is_checked'] = !$data['is_checked'];
            $data['last_checked_by'] = auth()->id();
        }
        $taskList->update($data);
    }

    public function destroy(TaskList $taskList)
    {
        if ($taskList->delete()) {
            return back();
        }
        return back()->withNotify('error', 'Cannot be deleted');
    }
}
