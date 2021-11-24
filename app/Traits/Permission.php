<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait Permission
{
    public function permissions(array &$validated, Model $model = null)
    {
        $validated['permissions'] = array_key_exists('all_perms', $validated) ? "all" : implode(',', $validated['perms'] ?? []);
        if(is_null($model)){
            $validated['permissions'] = empty(trim($validated['permissions'])) ? null : $validated['permissions'];
        }else{
            $validated['permissions'] = empty(trim($validated['permissions'])) ? ($model->getAttribute('permissions') ?? null) : $validated['permissions'];
        }
    }
}