<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function manage(User $user): bool
    {
        return $user->hasPermission('manage-company');
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-company');
    }

    public function delete(User $user, Company $inquiry)
    {
        return $user->hasPermission('delete-company');
    }
}
