<?php

namespace App\Models;

use App\Interfaces\DocumentableInterface;
use App\Interfaces\ResultableInterface;
use App\Traits\Documentable;
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
use const http\Client\Curl\AUTH_ANY;

/**
 * @property mixed $taskable
 */
class Task extends Model implements DocumentableInterface, ResultableInterface
{
    use HasFactory, SoftDeletes, Documentable, Resultable;

    public static function boot() {
        parent::boot();

        static::creating(function (Model $model) {
            $model->status = 'to_do';
        });

        static::updating(function (Model $model) {
            if ($model->isDirty('status') && $model->list()->exists() && $model->status == 'done'){
                TaskList::find($model->getRelationValue('list')->id)->update([
                    'is_checked' => 1,
                    'last_checked_by' => auth()->id()
                ]);
            }
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

    const DONE = 'done';

    public static function statuses(): array
    {
        return ['to_do', 'in_progress', self::DONE];
    }

    public static function priorities(): array
    {
        return ['low', 'medium', 'high', 'urgent'];
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
        return $this->belongsTo(User::class);
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
        return $this->hasMany(TaskList::class)->latest();
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

    public static function userCanViewAll(): bool
    {
        $user = auth()->user();
        return $user->isDeveloper() || $user->isAdministrator() || $user->hasPermission('viewAll-task');
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

    public function canManageTaskable()
    {
        switch ($this->taskable->getTable()){
            case 'departments':
                $departmentId = $this->taskable->id;
                break;
            case 'users':
                $departmentId = $this->taskable->department->id;
                break;
        }
        if (auth()->user()->hasPermission('department-chief') && $departmentId == auth()->user()->department->id){
            return true;
        }
        return false;
    }
}