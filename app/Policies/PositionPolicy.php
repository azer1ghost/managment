<?php

namespace App\Policies;

use App\Models\Position;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PositionPolicy
{
    use HandlesAuthorization;

    protected string $class = 'position';

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role->hasPermission(__FUNCTION__."-{$this->class}");
    }

    public function view(User $user, Position $position): bool
    {
        return $user->role->hasPermission(__FUNCTION__."-{$this->class}");
    }

    public function create(User $user): bool
    {
        return $user->role->hasPermission("manage-{$this->class}");
    }

    public function update(User $user, Position $position): bool
    {
        return $user->role->hasPermission("manage-{$this->class}");
    }

    public function delete(User $user, Position $position): bool
    {
        return $user->role->hasPermission("manage-{$this->class}");
    }
}
