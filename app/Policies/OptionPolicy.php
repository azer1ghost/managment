<?php

namespace App\Policies;

use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class OptionPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;
}
