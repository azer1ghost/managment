<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

class TaskObserver
{
    public function creating(Task $task)
    {
        $task->setAttribute('status', 'to_do');
    }

    public function updating(Task $task)
    {
        if ($task->isDirty('status') && $task->list()->exists() && $task->getAttribute('status') == 'done'){
            TaskList::find($task->getRelationValue('list')->id)->update([
                'is_checked' => 1,
                'last_checked_by' => auth()->id()
            ]);
        }
        if($task->isDirty('status') && $task->getAttribute('status') == $task::IN_PROGRESS &&
            $task->getAttribute('user_id') != auth()->id() &&
            $task->taskable->getTable() == 'departments' &&
            $task->taskable->id == auth()->user()->getAttribute('department_id')
        ){
            $task->setAttribute('taskable_type', User::class);
            $task->setAttribute('taskable_id', auth()->id());
        }
    }
}
