<?php

namespace App\Models;


use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Option extends Model implements Recordable
{
    use HasFactory, HasTranslations, SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    public array $translatable = ['text'];

    protected $fillable = ['text'];

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class)->withPivot('company_id', 'department_id');
    }

    public function subParameters(): HasMany
    {
        return $this->hasMany(Parameter::class);
    }

    public function inquires(): BelongsToMany
    {
        return $this->belongsToMany(Inquiry::class, 'inquiry_parameter', 'value')->withPivot('parameter_id');
    }

    public function barcodes(): BelongsToMany
    {
        return $this->belongsToMany(Inquiry::class, 'inquiry_parameter', 'value')->withPivot('parameter_id');
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'option_parameter')->withPivot('parameter_id', 'department_id');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'option_parameter')->withPivot('parameter_id', 'company_id');
    }
}
