<?php

namespace App\Policies;

use App\Models\Option;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OptionPolicy
{
    use HandlesAuthorization;

    protected string $class = 'option';

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role->hasPermission(__FUNCTION__."-{$this->class}");
    }

    public function view(User $user, Option $option): bool
    {
        return $user->role->hasPermission(__FUNCTION__."-{$this->class}");
    }

    public function create(User $user): bool
    {
        return $user->role->hasPermission("manage-{$this->class}");
    }

    public function update(User $user, Option $option): bool
    {
        return $user->role->hasPermission("manage-{$this->class}");
    }

    public function delete(User $user, Option $option): bool
    {
        return $user->role->hasPermission("manage-{$this->class}");
    }
}
