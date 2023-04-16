<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['user', 'department_id', 'message'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
