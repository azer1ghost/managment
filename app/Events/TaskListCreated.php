<?php

namespace App\Events;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class TaskListCreated
{
    use Dispatchable;

    public User $creator;
    public array $receivers = [];
    public string $title, $body, $url;

    public function __construct(TaskList $list)
    {
        $task = $list->getRelationValue('task');
        $this->url = route('tasks.show', $task);
        $this->creator = $list->getRelationValue('user');
        $this->title = $task->getAttribute('name') . ': ' . trans('translates.tasks.list.new');
        $this->body  = $list->getAttribute('name');

        switch ($task->taskable->getTable()) {
            case 'users':
                if($this->creator->getAttribute('id') != $task->taskable->id){
                    $this->receivers[] = $task->taskable; // get user to whom task is assigned
                }
                break;
            case 'departments':
                $this->receivers = $task->taskable->users()->whereNotIn('id', [
                    $this->creator->id,
                    $task->getRelationValue('user')->id
                ])->get()->all();
                break;
        }

        if ($this->creator->getAttribute('id') != $task->getRelationValue('user')->id) {
            $this->receivers[] = $task->getRelationValue('user'); // notify the user who created the task as well
        }
    }
}
