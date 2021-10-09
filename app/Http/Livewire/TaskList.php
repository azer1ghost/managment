<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class TaskList extends Component
{
    public ?Model $task;
    public string $todo = '';

    public function addToList()
    {
        if(empty($this->todo)) return;
        $this->task->taskLists()->create([
            'name' => $this->todo,
            'user_id' => auth()->id()
        ]);
        $content  = $this->todo;

        $this->todo = '';

        $url = config('app.url') . "/module/tasks/{$this->task->getAttribute('id')}";
        $users = $this->task->getAttribute('taskable_type') == 'App\Models\User' ?
            User::find($this->task->getAttribute('taskable_id')) :
            User::where('id', '!=', auth()->id())->where('department_id', $this->task->getAttribute('taskable_id'))->get();

        Notification::send($users, new TaskAssigned($content, $url, 'translates.tasks.list.new'));
    }

    public function removeFromList($id)
    {
        \App\Models\TaskList::find($id)->delete();
    }

    public function toggleState($id)
    {
        $list = \App\Models\TaskList::find($id);
        $list->update([
            'is_checked' => !$list->is_checked,
            'last_checked_by' => auth()->id(),
        ]);
    }

    public function render()
    {
        return view('panel.pages.tasks.components.task-list', [
            'taskList' => $this->task->taskLists()->with('task')->get()
        ]);
    }
}
