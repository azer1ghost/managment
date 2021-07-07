<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @method static where(string $string, string $string1)
 * @method static whereIn(string $string, string $string1)
 */
class Role extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    public function getPermissionsAttribute($value)
    {
        //return unserialize($value);
        return config('auth.permissions');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class);
    }
}
