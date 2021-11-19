<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskList;

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
    }
}
