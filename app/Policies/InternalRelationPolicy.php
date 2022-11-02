<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class InternalRelationPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, 'internalRelation', __FUNCTION__);
    }

    public function view(User $user): bool
    {
        return $this->canManage($user, 'internalRelation', __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, 'internalRelation');
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, 'internalRelation');
    }

    public function delete(User $user): bool
    {
        return $this->canManage($user, 'internalRelation', __FUNCTION__);
    }
}