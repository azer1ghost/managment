<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo, Relations\MorphTo, SoftDeletes};

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'inquiry_id', 'priority', 'note', 'status', 'must_start_at', 'must_end_at', 'done_at', 'done_by_user_id'];

    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
