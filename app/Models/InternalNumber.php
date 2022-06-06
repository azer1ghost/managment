<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalNumber extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'detail', 'phone'];
}
