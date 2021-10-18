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

    protected $listeners = ['isTaskDone' => '$refresh'];

    public function addToList()
    {
        if(empty($this->todo)) return;

        $this->dispatchBrowserEvent('contentChanged');

        $this->task->taskLists()->create([
            'name' => $this->todo,
            'user_id' => auth()->id()
        ]);
        $content  = $this->todo;

        $this->todo = '';

        // check if all lists are checked
        $this->emit('isTasksFinished');

        $url = config('app.url') . "/module/tasks/{$this->task->getAttribute('id')}";
        $users = $this->task->getAttribute('taskable_type') == 'App\Models\User' ?
            User::find($this->task->getAttribute('taskable_id')) :
            User::where('id', '!=', auth()->id())->where('department_id', $this->task->getAttribute('taskable_id'))->get();

        Notification::send($users, new TaskAssigned($content, $url, 'translates.tasks.list.new'));
    }

    public function removeFromList($id)
    {
        \App\Models\TaskList::find($id)->delete();

        // check if all lists are checked
        $this->emit('isTasksFinished');
    }

    public function toggleState($id)
    {
        $list = \App\Models\TaskList::find($id);
        $list->update([
            'is_checked' => !$list->is_checked,
            'last_checked_by' => auth()->id(),
        ]);

        // check if all lists are checked
        $this->emit('isTasksFinished');
    }

    public function render()
    {
        return view('panel.pages.tasks.components.task-list', [
            'taskList' => $this->task->taskLists()->with('task')->get()
        ]);
    }
}
