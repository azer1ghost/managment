<?php

namespace App\Traits;

trait UserAllowAccess
{
    public function hasPermission($perm): bool
    {
        if (app()->environment('local')){
            $permissions = config('auth.permissions');
        }else{
            $permissions = explode(',', $this->getAttribute('permissions'));
        }

        if($this->getAttribute('permissions') == 'all'){
            return true;
        }

        return in_array($perm, $permissions, true);
    }

    public function canManage($user, $class, $function = 'manage'): bool
    {
        return $user->hasPermission($function."-{$class}") ||
               $user->getRelationValue('role')->hasPermission($function."-{$class}") ||
               $user->getRelationValue('position')->hasPermission($function."-{$class}");
    }

}