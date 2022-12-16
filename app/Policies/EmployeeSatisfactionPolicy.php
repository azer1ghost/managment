<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeSatisfactionPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, 'employeeSatisfaction', __FUNCTION__);
    }

    public function view(User $user): bool
    {
        return $this->canManage($user, 'employeeSatisfaction', __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, 'employeeSatisfaction');
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, 'employeeSatisfaction');
    }

    public function delete(User $user): bool
    {
        return $this->canManage($user, 'employeeSatisfaction', __FUNCTION__);
    }
}
