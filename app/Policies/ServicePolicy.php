<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, Service $service): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $service->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function delete(User $user, Service $service): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
                $service->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function restore(User $user, Service $service): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $service->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function forceDelete(User $user, Service $service): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}