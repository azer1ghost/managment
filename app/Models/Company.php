<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static select(string[] $array)
 * @method static create($validated)
 * @method static insert(array $array)
 */
class Company extends Model
{
    protected $fillable = ['name', 'logo', 'website', 'mail', 'phone', 'mobile', 'address', 'about'];
    
}
