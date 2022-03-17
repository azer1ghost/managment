<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class InquiryPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function view(User $user, Inquiry $inquiry): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $this->canManage($user, $this->getClassShortName('s'), 'viewAll');
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, Inquiry $inquiry): \Illuminate\Auth\Access\Response
    {
        if (
            ($inquiry->getAttribute('user_id') === $user->getAttribute('id'))  &&
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) &&
            $user->canEditInquiry($inquiry)
//            auth()->user()->hasPermission('checkRejectedReason-inquiry')
        ) {
            return $this->allow();
        }

        return $this->deny('Your access right has expired.');
    }

    public function delete(User $user, Inquiry $inquiry): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) &&
            $inquiry->getAttribute('user_id') === $user->getAttribute('id') &&
            $user->canEditInquiry($inquiry);
    }

    public function restore(User $user, Inquiry $inquiry): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) &&
            $inquiry->getAttribute('user_id') === $user->getAttribute('id') &&
            $user->canEditInquiry($inquiry);
    }

    public function forceDelete(User $user, Inquiry $inquiry): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) &&
            $inquiry->getAttribute('user_id') === $user->getAttribute('id') &&
            $user->canEditInquiry($inquiry);
    }
}
