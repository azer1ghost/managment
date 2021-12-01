<?php

namespace App\Traits;

use App\Models\User;

trait HandlesPolicy
{
    public function canManage($user, $class, $function = 'manage'): bool
    {
        return $user->hasPermission("{$function}-{$class}");
    }

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function view(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }

    public function delete(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'));
    }
}