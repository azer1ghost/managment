<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use App\Traits\GetClassInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Department extends Model implements Recordable
{
    use HasTranslations, HasFactory, SoftDeletes, GetClassInfo, \Altek\Accountant\Recordable, Eventually;

    const SALES  = 7;

    protected $fillable = ['name', 'status', 'short_name', 'permissions'];

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

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class);
    }

    public function departmentCompanies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_parameter')->withPivot('parameter_id');
    }

    public function options($parameter_id = null): BelongsToMany
    {
        if(is_null($parameter_id)){
            return $this->belongsToMany(Option::class, 'option_parameter')->withPivot('parameter_id', 'company_id');
        }else{
            return $this->belongsToMany(Option::class, 'option_parameter')->withPivot('company_id')->withPivotValue('parameter_id', $parameter_id);
        }
    }

    public function getShortAttribute()
    {
        if (!$this->getAttribute('short_name')) {
            return $this->getAttribute('name');
        }
        return $this->getAttribute('short_name');
    }
}
