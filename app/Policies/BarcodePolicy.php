<?php

namespace App\Policies;

use App\Models\Barcode;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class BarcodePolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function view(User $user, Barcode $barcode): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $this->canManage($user, $this->getClassShortName('s'), 'viewAll');
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user,  Barcode $barcode): \Illuminate\Auth\Access\Response
    {
        if (($barcode->getAttribute('user_id') === $user->getAttribute('id'))  &&
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__))
         {
            return $this->allow();
         }

        return $this->deny('Your access right has expired.');
    }

    public function delete(User $user,  Barcode $barcode): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) &&
            $barcode->getAttribute('user_id') === $user->getAttribute('id');
    }
}
