<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\UserAllowAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization, UserAllowAccess, GetClassInfo;

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user)
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s'));

    }

    public function view(User $user, Task $task): bool
    {
        return
            ($user->isDeveloper() ||
            $user->isAdministrator() ||
            $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s')) ||
            $this->canAccessFunction($user, 'viewAll', $this->getClassShortName('s')));
    }

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
