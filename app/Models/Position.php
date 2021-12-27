<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Position extends Model implements Recordable
{
    use HasFactory, HasTranslations, SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    public array $translatable = ['name'];

    public $fillable = ['name', 'role_id', 'department_id', 'permissions', 'order'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class)->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault();
    }
}
