<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Task;
use Illuminate\Support\Collection;
use Livewire\Component;

class TaskForm extends Component
{
    public ?string $action = null, $method = null;
    public array $statuses, $priorities;
    public Collection $departments;
    public ?Task $task;
    public array $selected = [
        'status' => null,
        'priority' => null,
        'department' => null,
        'user' => null
    ];

    protected $listeners = ['statusChanged' => 'updateSelectedStatus'];

    public function getDepartmentProperty()
    {
        return Department::find($this->selected['department']);
    }

    public function mount()
    {
        $this->departments = Department::get(['id', 'name']);

        $this->statuses = Task::statuses();
        $this->priorities = Task::priorities();

        $task = optional(optional($this->task)->taskable);

        foreach (array_keys($this->selected) as $key){
            switch ($key) {
                case 'department':
                    $this->selected[$key] =
                        $task->getClassShortName() == $key ?
                            $task->getAttribute('id') :
                            optional($task->department)->getAttribute('id');
                    break;
                case 'user':
                    $this->selected[$key] =
                        $task->getClassShortName() == $key ? $task->getAttribute('id') : null;
                    break;
                default:
                    $this->selected[$key] = optional($this->task)->getAttribute($key);
            }
        }
    }

    public function updatedSelectedDepartment()
    {
        $this->selected['user'] = null;
    }

    public function updateSelectedStatus($oldValue, $newVal)
    {
        $this->task->update(['status' => $newVal]);
        if($this->task->canManageLists()) {
            if ($this->task->update(['status' => $newVal])) {
                $this->dispatchEvent('blue', 'Status updated', "Status updated from " . __('translates.fields.status.options.' . $oldValue) . " to " . __('translates.fields.status.options.' . $newVal));
            } else {
                $this->dispatchEvent('red', 'Error', 'Error encountered, please try again later');
            }
        }else{
            $this->dispatchEvent('red', 'Unauthorized', 'You cannot change status of this task');
        }
    }

    public function dispatchEvent($type, $title, $msg)
    {
        $this->dispatchBrowserEvent(
            'alert', [
            'type' => $type,
            'title' => $title,
            'message' => $msg
        ]);
    }

    public function render()
    {
        return view('panel.pages.tasks.components.task-form');
    }
}
