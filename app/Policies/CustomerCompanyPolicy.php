<?php

namespace App\Policies;

use App\Models\CustomerCompany;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerCompanyPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, CustomerCompany $customer_company): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $customer_company->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function delete(User $user, CustomerCompany $customer_company): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
                $customer_company->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function restore(User $user, CustomerCompany $customer_company): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $customer_company->getAttribute('user_id') == $user->getAttribute('id');
    }

    public function forceDelete(User $user, CustomerCompany $customer_company): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}