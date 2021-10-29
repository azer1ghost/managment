<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, Meeting $meeting): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $meeting->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function delete(User $user, Meeting $meeting): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
                $meeting->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function restore(User $user, Meeting $meeting): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $meeting->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function forceDelete(User $user, Meeting $meeting): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}