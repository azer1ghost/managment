<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasMany,
    Relations\MorphMany,
    Relations\MorphTo,
    SoftDeletes};

/**
 * @property mixed $taskable
 */
class Task extends Model
{
    use HasFactory, SoftDeletes;

    public static function boot() {
        parent::boot();

        static::creating(function (Model $model) {
            $model->status = 'to_do';
        });
    }

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

    public static function statuses(): array
    {
        return ['to_do', 'in_progress', 'done'];
    }

    public static function priorities(): array
    {
        return ['low', 'medium', 'high', 'urgent'];
    }

    public function taskable(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_viewer')->withTimestamps();
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function taskLists(): HasMany
    {
        return $this->hasMany(TaskList::class);
    }

    public function taskListsComplete($type = 'percent'): float
    {
        $overall = $this->taskLists()->count();
        $complete = $this->taskLists()->where('is_checked', true)->count();
        if ($type == 'percent'){
            return $overall == 0 ? 0 : round($complete/$overall, 2) * 100;
        }elseif ($type == 'overall'){
            return $overall;
        }
        else{
            return $complete;
        }
    }

    public function canManageLists(): bool
    {
        $user = auth()->user();
        $taskable_id = $this->taskable->getAttribute('id');

        return
            $this->getAttribute('user_id') == $user->getAttribute('id') ||
            ($this->getAttribute('taskable_type') === 'App\Models\User' ?
                $taskable_id == $user->getAttribute('id') :
                $taskable_id == $user->getRelationValue('department')->getAttribute('id')
            ) ||
            auth()->user()->isDeveloper();
    }
}