<?php

namespace App\Policies;

use App\Models\Conference;
use App\Models\Organization;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function delete(User $user, Organization $organization): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }
}

