<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Role extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    public function getPermissionsAttribute($value)
    {
        return unserialize($value);
    }
}
