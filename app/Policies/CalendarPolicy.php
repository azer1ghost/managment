<?php

namespace App\Policies;

use App\Models\Calendar;
use App\Models\User;
use App\Traits\GetClassInfo;
use App\Traits\HandlesPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalendarPolicy
{
    use HandlesAuthorization, HandlesPolicy, GetClassInfo;

    public function create(User $user): bool
    {
        return $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__);
    }

    public function update(User $user, Calendar $calendar): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $calendar->getAttribute('user_id') == $user->getAttribute('user_id');
    }

    public function delete(User $user, Calendar $calendar): bool
    {
        return
            $this->canManage($user, $this->getClassShortName('s'), __FUNCTION__) ||
            $calendar->getAttribute('user_id') == $user->getAttribute('user_id');
    }
}