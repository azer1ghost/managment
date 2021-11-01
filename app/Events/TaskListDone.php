<?php

namespace App\Events;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class TaskListDone
{
    use Dispatchable;

    public User $creator;
    public array $receivers = [];
    public string $title, $body, $url;

    public function __construct(TaskList $list, User $edited_by)
    {
        $task = $list->getRelationValue('task');
        $this->url = route('tasks.show', $task);
        $this->creator = $edited_by;
        $this->title = trans('translates.placeholders.task_name') . ': ' . $task->getAttribute('name');
        $this->body = trans('translates.tasks.list.checked', [
            'name' => $list->getAttribute('name'),
            'user' => $edited_by->getAttribute('fullname'),
        ]);

        switch ($task->taskable->getTable()) {
            case 'users':
                if($this->creator->getAttribute('id') != $task->taskable->id){
                    $this->receivers[] = $task->taskable; // get user to whom task is assigned
                }
                break;
            case 'departments':
                $this->receivers = $task->taskable->users()->whereNotIn('id', [
                    $this->creator->getAttribute('id'),
                    $list->getRelationValue('user')->id,
                    $task->getRelationValue('user')->id
                ])
                    ->get()->all();
                break;
        }

        if($task->getRelationValue('user')->id != $this->creator->getAttribute('id')){
            $this->receivers[] = $task->getRelationValue('user');
        }
        if(
            $list->getRelationValue('user')->id != $this->creator->getAttribute('id')  &&
            $list->getRelationValue('user')->id != $task->getRelationValue('user')->id
        ){
            $this->receivers[] = $list->getRelationValue('user');
        }
    }
}
