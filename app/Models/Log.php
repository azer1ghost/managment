<?php

namespace App\Models;

use App\Traits\Loger;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['user_id', 'action', 'data', 'message'];

    protected $casts = [
        'data' => 'object'
    ];

    public function logable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
