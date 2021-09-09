<?php

namespace App\Models;

use App\Traits\Loger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Position extends Model
{
    use HasFactory, HasTranslations, SoftDeletes, Loger;

    public array $translatable = ['name'];

    public $fillable = ['name', 'role_id', 'department_id', 'permissions'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
