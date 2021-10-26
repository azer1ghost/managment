<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Update extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'content', 'user_id', 'status', 'parent_id', 'datetime'];

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
        return $this->belongsTo(User::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public static function statuses()
    {
        return [ 1 => 'Rejected', 'Pending', 'Accepted', 'Started', 'Done', 'Upcoming', 'Error', 'Bug', 'Fixed'];
    }

    public function updates(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id')->latest();
    }
}