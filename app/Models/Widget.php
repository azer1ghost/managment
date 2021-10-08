<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Widget extends Model
{
    use HasTranslations;

    public array $translatable = ['details'];

    protected $fillable = [
        'key',
        'class_attribute',
        'style_attribute',
        'icon',
        'details',
        'order',
        'status'
    ];

    protected $casts = ['status' => 'boolean'];

    public function scopeIsActive($query)
    {
        return $query->where('status', 1);
    }
}
