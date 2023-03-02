<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LogisticClient extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'voen'];

}
