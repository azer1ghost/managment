<?php

namespace App\Policies;

use App\Models\SalesActivityType;
use App\Models\User;
use App\Traits\GetClassInfo;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HandlesPolicy;

class SalesActivityTypePolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, 'salesActivityType', __FUNCTION__);
    }

    public function view(User $user): bool
    {
        return $this->canManage($user, 'salesActivityType', __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, 'salesActivityType');
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, 'salesActivityType');
    }

    public function delete(User $user): bool
    {
        return $this->canManage($user, 'salesActivityType');
    }
}
