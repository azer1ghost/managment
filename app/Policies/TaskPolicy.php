<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function view(User $user, Task $task): bool
    {
        return
            $task->canManageTaskable() ||
            $task->canManageLists() ||
            $task->getAttribute('user_id') == $user->getAttribute('id') ||
            $user->isDirector();
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, Task $task): bool
    {
        return
            ( $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $task->getAttribute('user_id') == $user->getAttribute('id') ) &&
            is_null($task->getAttribute('done_at'));
    }

    public function delete(User $user, Task $task): bool
    {
        return
            ( $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
                $task->getAttribute('user_id') == $user->getAttribute('id') ) &&
            is_null($task->getAttribute('done_at'));
    }

    public function restore(User $user, Task $task): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $task->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}
