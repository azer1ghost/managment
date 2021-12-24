<?php

namespace App\Http\Livewire;

use App\Events\TaskCreated;
use App\Events\TaskStatusDone;
use App\Models\Department;
use App\Models\Inquiry;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\In;
use Livewire\Component;

class TaskForm extends Component
{
    public ?string $action = null, $method = null;
    public array $statuses, $priorities;
    public Collection $departments;
    public ?Task $task;
    public ?Inquiry $inquiry;

    public array $selected = [
        'status' => null,
        'priority' => null,
        'department' => null,
        'user' => null
    ];

    protected $listeners = [
        'statusChanging' => 'confirmStatusChange',
        'userChanging' => 'confirmUserChange',
        'statusChanged' => 'updateSelectedStatus',
        'userChanged' => 'updateSelectedUser',
        'taskListChecked' => '$refresh'
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

        $taskable = optional(optional($this->task)->taskable);

        foreach (array_keys($this->selected) as $key){
            switch ($key) {
                case 'department':
                    $this->selected[$key] =
                        request()->has('department') ? request()->get('department') :
                            ($taskable->getClassShortName() == $key ?
                                $taskable->getAttribute('id') :
                                optional($taskable->department)->getAttribute('id'));
                    break;
                case 'user':
                    $this->selected[$key] =
                        $taskable->getClassShortName() == $key ? $taskable->getAttribute('id') : null;
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
            $newVal,
            'status'
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
                if($newVal == 'done'){
                    event(new TaskStatusDone($this->task, auth()->user(), $oldValue, $newVal));
                }
            } else {
                $this->dispatchEvent('alert', 'red', 'Error', 'Error encountered, please try again later');
            }
        }else{
            $this->dispatchEvent('alert', 'red', 'Unauthorized', 'You cannot change status of this task');
        }
    }

    public function confirmUserChange($oldValue, $newVal)
    {
        $this->dispatchEvent(
            'alertChange',
            'red',
            __('translates.flash_messages.task_user_updated.confirm.title', ['name' => $this->task->getAttribute('name')]),
            __('translates.flash_messages.task_user_updated.confirm.msg',   [
                'prev' => User::find($oldValue)->fullname ?? trans('translates.filters.select'),
                'next' => User::find($newVal)->fullname ?? trans('translates.filters.select')
            ]),
            $oldValue,
            $newVal,
            'user'
        );
    }

    public function updateSelectedUser($oldValue, $newVal)
    {
        $data = [];
        $data['taskable_type'] = User::class;
        $data['taskable_id']   = $newVal;
        if ($this->task->update($data)) {
            $this->dispatchEvent(
                'alert',
                'blue',
                __('translates.flash_messages.task_user_updated.title', ['name' => $this->task->getAttribute('name')]),
                __('translates.flash_messages.task_user_updated.msg', [
                    'prev' => User::find($oldValue)->fullname ?? trans('translates.filters.select'),
                    'next' => User::find($newVal)->fullname ?? trans('translates.filters.select')
                ]));
            event(new TaskCreated($this->task));
        } else {
            $this->dispatchEvent('alert', 'red', 'Error', 'Error encountered, please try again later');
        }
    }

    public function dispatchEvent($name, $type, $title, $msg, $old = null, $new = null, $selected = null)
    {
        $this->dispatchBrowserEvent(
            $name, [
            'type' => $type,
            'title' => $title,
            'message' => $msg,
            'old' => $old,
            'new' => $new,
            'selected' => $selected
        ]);
    }

    public function render()
    {
        return view('panel.pages.tasks.components.task-form');
    }
}
