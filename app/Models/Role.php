<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * @method static where(string $string, string $string1)
 * @method static whereIn(string $string, string $string1)
 */
class Role extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    public function hasPermission($perm): bool
    {
        //$permissions = unserialize($value);

        $permissions = config('auth.permissions');

        return in_array($perm, $permissions, true);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
