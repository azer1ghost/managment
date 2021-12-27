<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Interfaces\DocumentableInterface;
use App\Traits\Documentable;
use App\Traits\GetClassInfo;
use App\Traits\Resultable;
use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasMany,
    Relations\HasOne,
    Relations\MorphMany,
    Relations\MorphTo,
    SoftDeletes};

/**
 * @property mixed $taskable
 */
class Task extends Model implements DocumentableInterface, Recordable
{
    use HasFactory, SoftDeletes, Documentable, Resultable, GetClassInfo, \Altek\Accountant\Recordable, Eventually;

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

    public const TO_DO = 'to_do';
    public const IN_PROGRESS = 'in_progress';
    public const DONE = 'done';

    protected $casts = ['must_start_at' => 'datetime', 'must_end_at' => 'datetime', 'done_at' => 'datetime'];

    public function getMainColumn(): string
    {
        return $this->getAttribute('name');
    }

    public static function statuses(): array
    {
        return [self::TO_DO, self::IN_PROGRESS, self::DONE];
    }

    public static function priorities(): array
    {
        return [0, 1, 2, 3];
    }

    public static function types(): array
    {
        return [
            1 => 'assigned_to_me',
            'my_tasks',
            'all'
        ];
    }

    public function taskable(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_viewer')->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class)->withDefault();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function taskLists(): HasMany
    {
        return $this->hasMany(TaskList::class)->orderBy('is_checked')->latest('id');
    }

    public static function userCanViewAll(): bool
    {
        return auth()->user()->hasPermission('viewAll-task');
    }

    public static function userCannotViewAll(): bool
    {
        return !self::userCanViewAll();
    }

    public static function userCanViewDepartmentTasks(): bool
    {
        return auth()->user()->hasPermission('viewAllDepartment-task');
    }

    public static function userCannotViewDepartmentTasks(): bool
    {
        return !self::userCanViewDepartmentTasks();
    }

    public function isFinished(): bool
    {
        if($this->taskLists()->where('is_checked', 0)->count() > 0){
            return false;
        }
        return true;
    }

    public function list(): HasOne
    {
        return $this->hasOne(TaskList::class, 'parent_task_id');
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
            );
    }

    public function canManageTaskable()
    {
        $departmentId = null;
        switch ($this->taskable->getTable()) {
            case 'departments':
                $departmentId = $this->taskable->id;
                break;
            case 'users':
                $departmentId = $this->taskable->department->id;
                break;
        }

        if (auth()->user()->hasPermission('department-chief') && $departmentId == auth()->user()->department->id) {
            return true;
        }

        return false;
    }
}