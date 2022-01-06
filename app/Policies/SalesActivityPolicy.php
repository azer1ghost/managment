<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesActivityPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, 'salesActivity', __FUNCTION__);
    }

    public function view(User $user): bool
    {
        return $this->canManage($user, 'salesActivity', __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, 'salesActivity');
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, 'salesActivity');
    }

    public function delete(User $user): bool
    {
        return $this->canManage($user, 'salesActivity');
    }
}