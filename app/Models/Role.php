<?php

namespace App\Models;

use App\Traits\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

/**
 * @method static where(string $string, string $string1)
 * @method static whereIn(string $string, string $string1)
 */
class Role extends Model
{
    use HasTranslations, Permission;

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
