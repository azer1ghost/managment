<?php

namespace App\Http\Controllers;

use App\Events\TaskListCreated;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function __construct(){
        $this->middleware('throttle:3,1')->only('update');
    }

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

        event(new TaskListCreated($list));

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
//            event(new TaskListDone($taskList, auth()->user()));
        }

        $taskList->update($data);

        return redirect($data['url'] . '#task-lists-header');
    }

    public function destroy(Request $request, TaskList $taskList)
    {
//        if ($taskList->delete()) {
//            return redirect($request->url . '#task-lists-header');
//        }
//        return back()->withNotify('error', 'Cannot be deleted');
        if ($taskList->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
