<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gadget extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'name',
        'labels',
        'colors',
        'icon',
        'color',
        'bg_color',
        'details',
        'query',
        'order',
        'status'
    ];

    protected $casts = ['status' => 'boolean'];
}
