<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProtocolPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, 'protocol', __FUNCTION__);
    }

    public function view(User $user): bool
    {
        return $this->canManage($user, 'protocol', __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, 'protocol');
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, 'protocol');
    }

    public function delete(User $user): bool
    {
        return $this->canManage($user, 'protocol', __FUNCTION__);
    }
}