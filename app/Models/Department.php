<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Department extends Model
{
    use HasTranslations, HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public array $translatable = ['name'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
