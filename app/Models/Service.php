<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasMany,
    SoftDeletes};
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ['name', 'detail', 'icon', 'company_id', 'department_id', 'service_id'];

    public array $translatable = ['name'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class, 'service_parameter');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'service_id')->withDefault();
    }

    public function services(): HasMany
    {
        return $this->hasMany(__CLASS__, 'service_id');
    }
}
