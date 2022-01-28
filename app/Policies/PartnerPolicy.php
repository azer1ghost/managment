<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartnerPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

}