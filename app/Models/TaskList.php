<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskList extends Model
{
    protected $table = 'task_lists';

    protected $fillable = ['task_id', 'name', 'is_checked', 'user_id', 'last_checked_by'];

    protected $casts = ['is_checked' => 'boolean'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_checked_by');
    }

    public function canManage(): bool
    {
        return $this->getAttribute('user_id') == auth()->id() ||
               $this->getRelationValue('task')->getAttribute('user_id') == auth()->id() ||
               auth()->user()->isDeveloper();
    }
}