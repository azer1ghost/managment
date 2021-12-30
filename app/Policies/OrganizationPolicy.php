<?php

namespace App\Policies;

use App\Models\Conference;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, Conference $conference): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $conference->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function delete(User $user, Conference $conference): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $conference->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function restore(User $user, Conference $conference): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $conference->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function forceDelete(User $user, Conference $conference): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}

