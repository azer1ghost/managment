<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    protected $fillable = ['name', 'logo', 'website', 'mail', 'phone', 'mobile', 'address', 'about'];

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class);
    }
}
