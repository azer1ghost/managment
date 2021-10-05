<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Model;
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
        $this->todo = '';
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
