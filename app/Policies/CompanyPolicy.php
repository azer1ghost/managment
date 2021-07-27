<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role->hasPermission('viewAny-company');
    }

    public function view(User $user, Company $company): bool
    {
        return $user->role->hasPermission('view-company');
    }

    public function create(User $user): bool
    {
        return $user->role->hasPermission('manage-company');
    }

    public function update(User $user, Company $company): bool
    {
        return $user->role->hasPermission('manage-company');
    }

    public function delete(User $user, Company $inquiry): bool
    {
        return $user->role->hasPermission('manage-company');
    }
}
