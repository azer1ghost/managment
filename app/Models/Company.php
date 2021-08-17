<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static select(string[] $array)
 * @method static create(array $validated)
 * @method static find($selectedCompany)
 */
class Company extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'logo', 'website', 'mail', 'phone', 'mobile', 'address', 'about'];

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class);
    }

    public function socials(): HasMany
    {
        return $this->hasMany(Social::class);
    }
}
