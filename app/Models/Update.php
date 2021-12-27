<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Update extends Model implements Recordable
{
    use HasFactory, SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['name', 'content', 'user_id', 'status', 'parent_id', 'datetime', 'done_at'];

    protected static function boot()
    {
        parent::boot();
        static::updating(function ($model){
            if($model->parent_id != null){
                $model->datetime = null;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public static function statuses()
    {
        return [
            1 => [
                'name' => 'Rejected',
                'color' => 'danger',
                'default' => false
            ],
            [
                'name' => 'Pending',
                'color' => 'warning',
                'default' => true
            ],
            [
                'name' => 'Accepted',
                'color' => 'success',
                'default' => false
            ],
            [
                'name' => 'Started',
                'color' => 'info',
                'default' => false
            ],
            [
                'name' => 'Done',
                'color' => 'success',
                'default' => false
            ],
            [
                'name' => 'Upcoming',
                'color' => 'warning',
                'default' => false
            ],
            [
                'name' => 'Error',
                'color' => 'danger',
                'default' => false
            ],
            [
                'name' => 'Bug',
                'color' => 'warning',
                'default' => false
            ],
            [
                'name' => 'Fixed',
                'color' => 'primary',
                'default' => false
            ],
        ];
    }

    public function updates(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id')->latest();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id')->withDefault();
    }
}