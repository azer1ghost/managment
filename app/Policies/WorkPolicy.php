<?php

namespace App\Policies;

use App\Models\Work;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, Work $work): bool
    {
        if(!is_null($work->getAttribute('price_verified_at')))
        {
            return false;
        }

        if($work->getAttribute('status') == $work::DONE && $user->hasPermission('editEarning-work'))
        {
            return true;
        }

        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $work->getAttribute('creator_id') == $user->getAttribute('id') ||
            (is_null($work->getAttribute('user_id'))  && $work->getAttribute('department_id') == $user->getAttribute('department_id')) ||
            $work->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function delete(User $user, Work $work): bool
    {
        if(!is_null($work->getAttribute('verified_at')) || $work->getAttribute('status') == $work::DONE)
        {
            return false;
        }

        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $work->getAttribute('creator_id') == $user->getAttribute('id');
    }

    public function restore(User $user, Work $work): bool
    {
        if($work->getAttribute('status') == $work::DONE && $user->hasPermission('editEarning-work'))
        {
            return true;
        }
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $work->getAttribute('creator_id') == $user->getAttribute('id') ||
            $work->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function forceDelete(User $user, Work $work): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}