<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Parameter extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

}
