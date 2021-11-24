<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait Permission
{
    public function permissions(array &$validated, Model $model)
    {
        $validated['permissions'] = array_key_exists('all_perms', $validated) ? "all" : implode(',', $validated['perms'] ?? []);
        $validated['permissions'] = empty(trim($validated['permissions'])) ? $model->getAttribute('permissions') : $validated['permissions'];
    }
}