<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Organization extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ['name', 'detail', 'is_certificate'];

    public array $translatable = ['name', 'detail'];
}
