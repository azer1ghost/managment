<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    SoftDeletes};
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ['name', 'detail', 'company_id', 'department_id'];

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
}
