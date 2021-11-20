<?php

namespace App\Models;

use App\Traits\GetClassInfo;
use App\Traits\Loger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Department extends Model
{
    use HasTranslations, HasFactory, SoftDeletes, Loger, GetClassInfo;

    protected $fillable = ['name', 'status', 'short_name'];

    public array $translatable = ['name', 'short_name'];

    public $casts = [
        'status' => 'boolean'
    ];

    public function scopeIsActive($query)
    {
        return $query->where('status', 1);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }
}
