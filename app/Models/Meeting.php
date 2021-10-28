<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, SoftDeletes};

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'status', 'datetime'];

    public static function statuses(): array
    {
        return ['deger1', 'deger2', 'deger3'];
    }
}
