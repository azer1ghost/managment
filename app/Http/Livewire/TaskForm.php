<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Task;
use Illuminate\Support\Collection;
use Livewire\Component;

class TaskForm extends Component
{
    public ?string $action, $method;
    public array $statuses, $priorities;
    public Collection $departments;
    public ?Task $task;
    public array $selected = [
        'status' => null,
        'priority' => null,
        'department' => null,
        'user' => null
    ];

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

    public function updatedSelectedStatus()
    {
        //
    }

    public function render()
    {
        return view('panel.pages.tasks.components.task-form');
    }
}
