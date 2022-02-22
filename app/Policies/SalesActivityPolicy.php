<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\SalesActivity;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesActivityPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, 'salesActivity', __FUNCTION__);
    }

    public function view(User $user, SalesActivity $salesActivity): bool
    {
        return $this->canManage($user, 'salesActivity', __FUNCTION__) || ($salesActivity->getAttribute('user_id') === $user->getAttribute('id')) || $user->hasPermission('viewAll-salesActivity');
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, 'salesActivity');
    }

    public function update(User $user, SalesActivity $salesActivity): bool
    {
        return $this->canManage($user, 'salesActivity',__FUNCTION__) || ($salesActivity->getAttribute('user_id') === $user->getAttribute('id'));
    }

    public function delete(User $user, SalesActivity $salesActivity): bool
    {
        return $this->canManage($user, 'salesActivity',__FUNCTION__) || ($salesActivity->getAttribute('user_id') === $user->getAttribute('id'));
    }
}