<?php

namespace App\Traits;

trait UserAllowAccess
{
    public function canManage($user, $class)
    {
        return $user->hasPermission("manage-{$class}") ||
               $user->getRelationValue('role')->hasPermission("manage-{$class}") ||
               $user->getRelationValue('position')->hasPermission("manage-{$class}");
    }

    public function canView($user, $function, $class)
    {
        return $user->hasPermission($function."-{$class}") ||
               $user->getRelationValue('role')->hasPermission($function."-{$class}") ||
               $user->getRelationValue('position')->hasPermission($function."-{$class}");
    }
}