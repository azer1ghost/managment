<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model implements Recordable
{
    use HasFactory, SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['content', 'user_id'];

    protected $with = ['user:id,name,surname,avatar'];

    public static function boot() {

        parent::boot();

        static::creating(function($comment) {
            $comment->user_id = auth()->id() ?? 1;
        });

    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo()->withDefault();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(self::class, 'commentable');
    }

    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comment_viewer')->withTimestamps();
    }

    public function wasViewed($user_id = null)
    {
        return $this->viewers()->where('user_id', $user_id ?? auth()->id())->first();
    }

    public function wasViewedAt($user_id = null)
    {
        return $this->wasViewed()->pivot->getAttribute('created_at');
    }
}