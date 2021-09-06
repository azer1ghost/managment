<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GetClassInfo;
use Illuminate\Auth\Access\HandlesAuthorization;

class GadgetPolicy
{
    use HandlesAuthorization, GetClassInfo;

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role->hasPermission(__FUNCTION__."-".$this->getClassShortName('s'));
    }

    public function view(User $user): bool
    {
        return $user->role->hasPermission(__FUNCTION__."-".$this->getClassShortName('s'));
    }

    public function create(User $user): bool
    {
        return $user->role->hasPermission("manage-".$this->getClassShortName('s'));
    }

    public function update(User $user): bool
    {
        return $user->role->hasPermission("manage-".$this->getClassShortName('s'));
    }

    public function delete(User $user): bool
    {
        return $user->role->hasPermission("manage-".$this->getClassShortName('s'));
    }
}
