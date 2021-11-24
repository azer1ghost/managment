<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdvertisingPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, Meeting $meeting): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function delete(User $user, Meeting $meeting): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}