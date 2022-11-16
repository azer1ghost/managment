<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class JobInstruction extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'instruction'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }

}
