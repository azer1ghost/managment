<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GetClassInfo;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HandlesPolicy;

class DatabaseNotificationPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;
}
