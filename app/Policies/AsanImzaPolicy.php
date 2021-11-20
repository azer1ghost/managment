<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GetClassInfo;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HandlesPolicy;

class AsanImzaPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function delete(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}
