<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\UserAllowAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization, UserAllowAccess, GetClassInfo;

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function view(User $user, Role $role): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }

    public function update(User $user, Role $role): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }

    public function delete(User $user, Role $role): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }
}

