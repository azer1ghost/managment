<?php

namespace App\Policies;

use App\Models\Parameter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParameterPolicy
{
    use HandlesAuthorization;

    protected string $class = 'parameter';

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role->hasPermission(__FUNCTION__."-{$this->class}");
    }

    public function view(User $user, Parameter $parameter): bool
    {
        return $user->role->hasPermission(__FUNCTION__."-{$this->class}");
    }

    public function create(User $user): bool
    {
        return $user->role->hasPermission("manage-{$this->class}");
    }

    public function update(User $user, Parameter $parameter): bool
    {
        return $user->role->hasPermission("manage-{$this->class}");
    }

    public function delete(User $user, Parameter $inquiry): bool
    {
        return $user->role->hasPermission("manage-{$this->class}");
    }
}
