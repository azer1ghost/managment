<?php

namespace App\Policies;

use App\Models\Result;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResultPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function view(User $user, Result $result): bool
    {
        return $result->canManageLists();
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, Result $result): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $result->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function delete(User $user, Result $result): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $result->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function restore(User $user, Result $result): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $result->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function forceDelete(User $user, Result $result): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}
