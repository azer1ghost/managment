<?php

namespace App\Policies;

use App\Models\Conference;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConferencePolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;


}