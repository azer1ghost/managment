<?php

namespace App\Events;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class TaskCreated
{
    use Dispatchable;

    public User $creator;
    public array $receivers = [];
    public string $title, $body = '', $url;

    public function __construct(Task $task)
    {
        $this->url = route('tasks.show', $task);
        $this->creator = $task->getRelationValue('user');
        $this->title = trans('translates.tasks.new');

        switch ($task->taskable->getTable()) {
            case 'users':
                $this->body = trans('translates.tasks.content.user');
                $this->receivers[] = $task->taskable; // get user to whom task is assigned
                break;
            case 'departments':
                $this->body = trans('translates.tasks.content.department');
                $this->receivers = $task->taskable->users()->whereNotIn('id', [$this->creator->id])->get()->all();
                break;
        }
    }
}