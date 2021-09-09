<?php

namespace App\Traits;

trait HandlesPolicy
{
    public function canManage($user, $class, $function = 'manage'): bool
    {
        return $user->hasPermission("{$function}-{$class}");
    }
}