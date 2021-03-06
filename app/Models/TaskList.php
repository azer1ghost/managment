<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskList extends Model implements Recordable
{
    use \Altek\Accountant\Recordable, Eventually;

    protected $table = 'task_lists';

    protected $fillable = ['task_id', 'name', 'is_checked', 'user_id', 'last_checked_by', 'parent_task_id'];

    protected $casts = ['is_checked' => 'boolean'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class)->withDefault();
    }

    public function parentTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_task_id')->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_checked_by')->withDefault();
    }

    public function canManage(): bool
    {
        return $this->getAttribute('user_id') == auth()->id() ||
               $this->getRelationValue('task')->getAttribute('user_id') == auth()->id() ||
               auth()->user()->isDeveloper();
    }
}