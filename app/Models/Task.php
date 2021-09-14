<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo, Relations\MorphTo, SoftDeletes};

/**
 * @property mixed $taskable
 */
class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'inquiry_id',
        'priority',
        'note',
        'status',
        'must_start_at',
        'must_end_at',
        'done_at',
        'done_by_user_id',
        'user_id',
        'taskable_type',
        'taskable_id',
    ];

    public static function statuses()
    {
        return ['to_do', 'in_progress', 'done'];
    }

    public static function priorities()
    {
        return ['low', 'medium', 'high', 'urgent'];
    }

    public function taskable(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class)->withDefault();
    }
}
