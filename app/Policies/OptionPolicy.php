<?php

namespace App\Policies;

use App\Models\Option;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\UserAllowAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class OptionPolicy
{
    use HandlesAuthorization, UserAllowAccess, GetClassInfo;

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s'));
    }

    public function view(User $user, Option $option): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s'));
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }

    public function update(User $user, Option $option): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }

    public function delete(User $user, Option $option): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }
}
