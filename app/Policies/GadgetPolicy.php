<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\UserAllowAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class GadgetPolicy
{
    use HandlesAuthorization, UserAllowAccess, GetClassInfo;

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canAccessFunction($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function view(User $user): bool
    {
        return $this->canAccessFunction($user, $this->getClassShortName('s'),  __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canAccessFunction($user, $this->getClassShortName('s'));
    }

    public function update(User $user): bool
    {
        return $this->canAccessFunction($user, $this->getClassShortName('s'));
    }

    public function delete(User $user): bool
    {
        return $this->canAccessFunction($user, $this->getClassShortName('s'));
    }
}
