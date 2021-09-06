<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    protected string $class = 'department';

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->class);
    }

    public function view(User $user, Department $department): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->class);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->class);
    }

    public function update(User $user, Department $department): bool
    {
        return $this->canManage($user, $this->class);
    }

    public function delete(User $user, Department $department): bool
    {
        return $this->canManage($user, $this->class);
    }
}

