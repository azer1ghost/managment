<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Widget extends Model implements Recordable
{
    use HasTranslations, \Altek\Accountant\Recordable, Eventually;

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
