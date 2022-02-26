<?php

namespace App\Observers;

use App\Events\TaskCreated;
use App\Models\Inquiry;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Services\CacheService;

class TaskObserver
{
    /**
     * @var CacheService $cacheService
     */
    private CacheService $cacheService;

    /**
     * Create a new job instance.
     *
     * @param CacheService $cacheService
     * @return void
     */
    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function creating(Task $task)
    {
        $task->setAttribute('status', $task::TO_DO);
        $task->setAttribute('user_id', auth()->id());
    }

    public function created(Task $task)
    {
        if($task->inquiry()->exists()){
            $task->getRelationValue('inquiry')
                ->parameters()
                ->updateExistingPivot(Inquiry::STATUS_PARAMETER, ['value' => Inquiry::REDIRECTED]);
        }
    }

    public function updating(Task $task)
    {
        if ($task->isDirty('status') && $task->list()->exists() && $task->getAttribute('status') == 'done'){
            TaskList::find($task->getRelationValue('list')->id)->update([
                'is_checked' => 1,
                'last_checked_by' => auth()->id()
            ]);
        }

        if($task->isDirty('status') && $task->getAttribute('status') == $task::IN_PROGRESS &&
            $task->getAttribute('user_id') != auth()->id() &&
            $task->taskable->getTable() == 'departments' &&
            $task->taskable->id == auth()->user()->getAttribute('department_id')
        ){
            $task->setAttribute('taskable_type', User::class);
            $task->setAttribute('taskable_id', auth()->id());
        }

        if($task->isDirty('status') && $task->getAttribute('status') == $task::DONE){
            $task->setAttribute('done_at', now());
            $task->setAttribute('done_by_user_id', auth()->id());

            if($task->inquiry()->exists()){
                $task->getRelationValue('inquiry')
                    ->parameters()
                    ->updateExistingPivot(Inquiry::STATUS_PARAMETER, [
                        'value' => Inquiry::DONE
                    ]);
            }
        }

//        if($task->isDirty('taskable_type') || $task->isDirty('taskable_id')) {
//            $task->setAttribute('status', Task::TO_DO);
//            event(new TaskCreated($task));
//        }
    }
}
