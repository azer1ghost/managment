<?php

namespace App\Policies;

use App\Models\Update;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class UpdatePolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, Update $update): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $update->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function delete(User $user, Update $update): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
                $update->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function restore(User $user, Update $update): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $update->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function forceDelete(User $user, Update $update): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}