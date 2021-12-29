<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GetClassInfo;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HandlesPolicy;

class CertificatePolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function viewAny(User $user): bool
    {
        return $this->canManage($user, 'certificate', __FUNCTION__);
    }

    public function view(User $user): bool
    {
        return $this->canManage($user, 'certificate', __FUNCTION__);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user, 'certificate');
    }

    public function update(User $user): bool
    {
        return $this->canManage($user, 'certificate');
    }

    public function delete(User $user): bool
    {
        return $this->canManage($user, 'certificate', __FUNCTION__);
    }
}
