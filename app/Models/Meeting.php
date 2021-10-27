<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','status','datetime'];

    public static function statuses()
    {
        return [ 1 => 'deger1', 'deger2', 'deger3'];
    }
}
