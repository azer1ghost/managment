<?php

namespace App\Events;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class TaskStatusUpdated
{
    use Dispatchable;

    public User $creator;
    public array $receivers = [];
    public string $title, $body, $url;

    public function __construct(Task $task, User $edited_by, $prev, $next)
    {
        $this->url = route('tasks.show', $task);
        $this->creator = $edited_by;
        $this->title = trans('translates.placeholders.task_name') . ': ' . $task->getAttribute('name');
        $this->body = trans('translates.flash_messages.task_status_updated.msg', [
            'prev' => trans('translates.fields.status.options.' . $prev),
            'next' => trans('translates.fields.status.options.' . $next),
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
                    $task->getRelationValue('user')->id
                ])
                    ->get()->all();
                break;
        }

        $this->receivers[] = $task->user(); // notify the user who created the task as well
    }
}
