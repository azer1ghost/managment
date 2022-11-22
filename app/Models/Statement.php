<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Statement extends Model
{
    use SoftDeletes, Notifiable;

    protected $fillable = ['title', 'body', 'attribute'];
}
