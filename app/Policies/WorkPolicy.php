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
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $work->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function delete(User $user, Work $work): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
                $work->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function restore(User $user, Work $work): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $work->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function forceDelete(User $user, Work $work): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}