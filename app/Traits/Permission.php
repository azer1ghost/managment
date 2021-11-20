<?php

namespace App\Traits;

trait Permission
{
    public function permissions(&$validated)
    {
        $validated['permissions'] = array_key_exists('all_perms', $validated) ? "all" : implode(',', $validated['perms'] ?? []);
        $validated['permissions'] = empty(trim($validated['permissions'])) ? null : $validated['permissions'];
    }
}