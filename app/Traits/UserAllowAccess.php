<?php

namespace App\Traits;

trait UserAllowAccess
{
    public function canManage($user, $class, $function = 'manage'): bool
    {
        return $user->hasPermission("{$function}-{$class}");
    }
}