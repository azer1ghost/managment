<?php

namespace App\Events;

use App\Models\Task;
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
        $this->creator = $task->getRelationValue('user');
        $this->title = trans('translates.tasks.list.new');
        $this->body = $list->getAttribute('name');

        switch ($task->taskable->getTable()) {
            case 'users':
                $this->receivers[] = $task->taskable; // get user to whom task is assigned
                break;
            case 'departments':
                $this->receivers = $task->taskable->users()->whereNotIn('id', [$this->creator->id])->get()->all();
                break;
        }
    }
}
