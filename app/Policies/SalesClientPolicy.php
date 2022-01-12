<?php

namespace App\Policies;

use App\Models\SalesClient;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesClientPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, 'salesClient', __FUNCTION__);
    }

    public function view(User $user): bool
    {
        return $this->canManage($user, 'salesClient', __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, 'salesClient');
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, 'salesClient');
    }

    public function delete(User $user, SalesClient $salesClient): bool
    {
        return $this->canManage($user, 'salesClient', __FUNCTION__ ) || ($salesClient->getAttribute('user_id') == $user->getAttribute('id') && $salesClient->getAttribute('created_at')->diff(now())->h < 24);
    }
}