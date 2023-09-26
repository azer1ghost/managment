<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class FundPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('fund'), __FUNCTION__);
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('fund'), __FUNCTION__);
    }

    public function delete(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('fund'), __FUNCTION__);
    }
}