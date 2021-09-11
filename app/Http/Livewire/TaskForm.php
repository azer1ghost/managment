<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class TaskForm extends Component
{
    public ?string $action, $method, $status = '', $priority = '',  $department = '', $user = '';
    public ?Task $task;
    public Collection $departments, $users;
    public array $statuses, $priorities;

    public function mount($task)
    {
        $this->task = $task;
        $this->users = collect();
        $this->departments = Department::get(['id', 'name']);
        $this->statuses = Task::$statuses;
        $this->priorities = Task::$priorities;
        $this->status = optional($this->task)->getAttribute('status') ?? '';
        $this->priority = optional($this->task)->getAttribute('priority') ?? '';

        $this->department = \Str::contains('App\Models\User', optional($this->task)->getAttribute('taskable_type')) ?
            User::find(optional($this->task)->getAttribute('taskable_id'))->getRelationValue('department')->getAttribute('id') :
            (\Str::contains('App\Models\Department', optional($this->task)->getAttribute('taskable_type')) ?
            optional($this->task)->getAttribute('taskable_id') : '');

        $this->updatedDepartment($this->department);
        $this->user = \Str::contains('App\Models\User', optional($this->task)->getAttribute('taskable_type')) ?
            optional($this->task)->getAttribute('taskable_id') : '';
    }

    public function updatedDepartment($value)
    {
        $this->user = '';
        $this->users = User::where('department_id', $value)->get(['id', 'name', 'surname']);
    }

//    public function updatedUser($value)
//    {
//        $this->user = User::where('department_id', $value)->pluck('name', 'id')->toArray();
//    }

    public function render()
    {
        return view('panel.pages.tasks.components.task-form');
    }
}
