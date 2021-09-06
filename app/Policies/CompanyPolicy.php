<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\UserAllowAccess;

class CompanyPolicy
{
    use HandlesAuthorization, UserAllowAccess;

    protected string $class = 'company';

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->class);
    }

    public function view(User $user, Company $company): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->class);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->class);
    }

    public function update(User $user, Company $company): bool
    {
        return $this->canManage($user, $this->class);
    }

    public function delete(User $user, Company $company): bool
    {
        return $this->canManage($user, $this->class);
    }
}
