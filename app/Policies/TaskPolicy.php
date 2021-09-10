<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use App\Traits\UserAllowAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s'));
    }

    public function update(User $user, Task $task): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s'));
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s'));
    }

    public function restore(User $user, Task $task): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s'));
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s'));
    }
}
