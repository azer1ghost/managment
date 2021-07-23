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
        if ($user->isDeveloper() OR $user->isAdministrator()){
            return true;
        }

        return null;
    }

    public function manage(User $user): bool
    {
        return $user->hasPermission('manage-company');
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-company');
    }

    public function delete(User $user, Company $inquiry): bool
    {
        return $user->hasPermission('delete-company');
    }
}
