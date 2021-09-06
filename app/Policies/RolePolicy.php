<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    protected string $class = 'role';

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->class);
    }

    public function view(User $user, Role $role): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->class);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->class);
    }

    public function update(User $user, Role $role): bool
    {
        return $this->canManage($user, $this->class);
    }

    public function delete(User $user, Role $role): bool
    {
        return $this->canManage($user, $this->class);
    }
}

