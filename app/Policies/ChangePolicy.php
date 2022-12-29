<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChangePolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, 'change', __FUNCTION__);
    }

    public function view(User $user): bool
    {
        return $this->canManage($user, 'change', __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, 'change');
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, 'change');
    }

    public function delete(User $user): bool
    {
        return $this->canManage($user, 'change', __FUNCTION__);
    }
}