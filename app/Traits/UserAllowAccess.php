<?php

namespace App\Traits;

trait UserAllowAccess
{
    public function canManage($user, $class, $function = 'manage')
    {
        return $user->hasPermission($function."-{$class}") ||
               $user->getRelationValue('role')->hasPermission($function."-{$class}") ||
               $user->getRelationValue('position')->hasPermission($function."-{$class}");
    }
}