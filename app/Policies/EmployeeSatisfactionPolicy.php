<?php

namespace App\Policies;

use App\Models\EmployeeSatisfaction;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeSatisfactionPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function viewAny(User $user, EmployeeSatisfaction $satisfaction): bool
    {
        return $this->canManage($user, 'employeeSatisfaction', __FUNCTION__) &&
            $satisfaction->getAttribute('user_id') === $user->getAttribute('id');
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
