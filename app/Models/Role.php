<?php

namespace App\Models;

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
    use HasTranslations;

    public array $translatable = ['name'];
    protected $fillable = ['name', 'key', 'permissions'];

    public function hasPermission($perm): bool
    {
        if (env('APP_ENV','local') == 'local'){
            $permissions = config('auth.permissions');
        }else{
            $permissions = explode(',', $this->getAttribute('permissions'));
        }
        if($this->getAttribute('permissions') == 'all'){
            return true;
        }
        return in_array($perm, $permissions, true);
    }

    public function getShortPermissionsAttribute()
    {
        return Str::limit($this->getAttribute('permissions'),60);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
