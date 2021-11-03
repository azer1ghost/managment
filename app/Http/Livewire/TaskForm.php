<?php

namespace App\Http\Livewire;

use App\Events\TaskStatusUpdated;
use App\Models\Department;
use App\Models\Inquiry;
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

    protected $listeners = [
        'statusChanging' => 'confirmStatusChange',
        'statusChanged' => 'updateSelectedStatus',
        'isTasksFinished' => '$refresh'
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

    public function confirmStatusChange($oldValue, $newVal)
    {
        $this->dispatchEvent(
            'alertChange',
            'red',
            __('translates.flash_messages.task_status_updated.confirm.title', ['name' => $this->task->getAttribute('name')]),
            __('translates.flash_messages.task_status_updated.confirm.msg',   [
                'prev' => __('translates.fields.status.options.'.$oldValue),
                'next' => __('translates.fields.status.options.'.$newVal)
            ]),
            $oldValue,
            $newVal
        );
    }

    public function updateSelectedStatus($oldValue, $newVal)
    {
        if($this->task->canManageLists()) {
            $data = [];
            if($newVal == 'done'){
                $data['done_at'] = now();
                $data['done_by_user_id'] = auth()->id();
            }
            $data['status'] = $newVal;

            if ($this->task->update($data)) {
                $this->dispatchEvent(
                    'alert',
                    'blue',
                    __('translates.flash_messages.task_status_updated.title', ['name' => $this->task->getAttribute('name')]),
                    __('translates.flash_messages.task_status_updated.msg', [
                        'prev' => __('translates.fields.status.options.' . $oldValue),
                        'next' => __('translates.fields.status.options.' . $newVal)
                ]));
                if($this->task->inquiry()->exists()){
                    $this->task->getRelationValue('inquiry')
                        ->parameters()
                        ->updateExistingPivot(Inquiry::STATUS_PARAMETER, ['value' => Inquiry::DONE]);
                }
                event(new TaskStatusUpdated($this->task, auth()->user(), $oldValue, $newVal));
            } else {
                $this->dispatchEvent('alert', 'red', 'Error', 'Error encountered, please try again later');
            }
        }else{
            $this->dispatchEvent('alert', 'red', 'Unauthorized', 'You cannot change status of this task');
        }
        if($newVal == 'done'){
            $this->emit('isTaskDone');
        }
    }

    public function dispatchEvent($name, $type, $title, $msg, $old = null, $new = null)
    {
        $this->dispatchBrowserEvent(
            $name, [
            'type' => $type,
            'title' => $title,
            'message' => $msg,
            'old' => $old,
            'new' => $new
        ]);
    }

    public function render()
    {
        return view('panel.pages.tasks.components.task-form');
    }
}
