<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationLog extends Model

{
    protected $fillable = ['performer', 'receiver', 'sender', 'number', 'description', 'arrived_at', 'received_at'];

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class,'performer')->withDefault();
    }
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class,'receiver')->withDefault();
    }
}
