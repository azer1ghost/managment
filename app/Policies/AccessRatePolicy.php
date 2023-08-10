<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccessRatePolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, 'accessRate', __FUNCTION__);
    }

    public function view(User $user): bool
    {
        return $this->canManage($user, 'accessRate', __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, 'accessRate', __FUNCTION__);
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, 'accessRate', __FUNCTION__);
    }

    public function delete(User $user): bool
    {
        return $this->canManage($user, 'accessRate', __FUNCTION__);
    }
}