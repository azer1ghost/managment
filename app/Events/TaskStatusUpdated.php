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
    public string $title, $body = '', $url;

    public function __construct(Task $task, User $edited_by, $prev, $next)
    {
        $this->url = route('tasks.show', $task);
        $this->creator = $edited_by;
        $this->title = trans('translates.placeholders.task_name') . ': ' . $task->getAttribute('name');
        $this->body = trans('translates.flash_messages.task_status_updated.msg', [
            'prev' => trans('translates.fields.status.options.' . $prev),
            'next' => trans('translates.fields.status.options.' . $next),
        ]);
        $this->receivers[] = $this->creator;
    }
}
