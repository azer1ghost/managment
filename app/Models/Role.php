<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

/**
 * @method static where(string $string, string $string1)
 * @method static whereIn(string $string, string $string1)
 */
class Role extends Model implements Recordable
{
    use HasTranslations, SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    public array $translatable = ['name'];

    protected $fillable = ['name', 'key', 'permissions'];

    public function getShortPermissionsAttribute(): string
    {
        return Str::limit($this->getAttribute('permissions'),60);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
}
