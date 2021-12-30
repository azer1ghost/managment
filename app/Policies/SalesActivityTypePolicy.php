<?php

namespace App\Policies;

use App\Models\SalesActivityType;
use App\Models\User;
use App\Traits\GetClassInfo;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HandlesPolicy;

class SalesActivityTypePolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

}
