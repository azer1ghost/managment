<?php

namespace App\Traits;

trait Permission
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
}