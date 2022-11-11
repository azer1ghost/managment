<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalNumber extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'detail', 'phone', 'user_id'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }
}
