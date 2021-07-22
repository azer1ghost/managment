<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static select(string[] $array)
 * @method static create(array $validated)
 */
class Company extends Model
{
    protected $fillable = ['name', 'logo', 'website', 'mail', 'phone', 'mobile', 'address', 'about'];

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class);
    }
}
