<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\UserAllowAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class InquiryPolicy
{
    use HandlesAuthorization, UserAllowAccess, GetClassInfo;

    public function before(User $user): ?bool
    {
        return $user->isDeveloper() || $user->isAdministrator() ? true: null;
    }

    public function viewAny(User $user)
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s'));

    }

    public function view(User $user, Inquiry $inquiry): bool
    {
        return
            ($user->isDeveloper() ||
            $user->isAdministrator() ||
            $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s')) ||
            $this->canAccessFunction($user, 'viewAll', $this->getClassShortName('s'))) &&
            $inquiry->getAttribute('user_id') === $user->getAttribute('id');
    }

    public function create(User $user): bool
    {
        return $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s'));

    }

    public function update(User $user, Inquiry $inquiry): \Illuminate\Auth\Access\Response
    {
        if (
            ($inquiry->getAttribute('user_id') === $user->getAttribute('id'))  &&
            $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s')) &&
            $user->canEditInquiry($inquiry)
        ) {
            return $this->allow();
        }

        return $this->deny('Your access right has expired.');
    }

    public function delete(User $user, Inquiry $inquiry): bool
    {
        return
            $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s')) &&
            $inquiry->getAttribute('user_id') === $user->getAttribute('id') &&
            $user->canEditInquiry($inquiry);
    }

    public function restore(User $user, Inquiry $inquiry): bool
    {
        return
            $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s')) &&
            $inquiry->getAttribute('user_id') === $user->getAttribute('id') &&
            $user->canEditInquiry($inquiry);
    }

    public function forceDelete(User $user, Inquiry $inquiry): bool
    {
        return
            $this->canAccessFunction($user, __FUNCTION__, $this->getClassShortName('s')) &&
            $inquiry->getAttribute('user_id') === $user->getAttribute('id') &&
            $user->canEditInquiry($inquiry);
    }
}
