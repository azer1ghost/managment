<?php

namespace App\Http\Controllers\Modules;

use App\Events\TaskListCreated;
use App\Http\Controllers\Controller;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function __construct(){
        $this->middleware('throttle:3,1')->only('update');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->id();

        $list = TaskList::create($data);

        event(new TaskListCreated($list));

        return redirect("{$request->get('url')}#task-lists-header");
    }

    public function update(Request $request, TaskList $taskList)
    {
        $data = $request->all();

        if(array_key_exists('is_checked', $data)){
            $data['last_checked_by'] = auth()->id();
        }

        $taskList->update($data);

        return redirect($data['url'] . '#task-lists-header');
    }

    public function destroy(TaskList $taskList)
    {
        if ($taskList->delete()) {
            if($taskList->parentTask()->exists()){
                $taskList->parentTask()->delete();
            }
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
