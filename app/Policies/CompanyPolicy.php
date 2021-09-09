<?php

namespace App\Policies;

use App\Traits\GetClassInfo;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HandlesPolicy;

class CompanyPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;
}
