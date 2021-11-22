<?php

namespace App\Models;

use App\Traits\Loger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static select(string[] $array)
 * @method static create(array $validated)
 * @method static find($selectedCompany)
 */
class Company extends Model
{
    use SoftDeletes, Loger;

    protected $fillable = [
        'name',
        'logo',
        'website',
        'mail',
        'call_center',
        'mobile',
        'address',
        'about',
        'keywords',
        'is_inquirable',
        'intercity_phone',
    ];

    protected $casts = [
        'is_inquirable' => 'boolean'
    ];

    public function scopeIsInquirable($query)
    {
        return $query->where('is_inquirable', 1);
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class);
    }

    public function options($parameter_id = null): BelongsToMany
    {
        if(is_null($parameter_id)){
            return $this->belongsToMany(Option::class, 'option_parameter')->withPivot('parameter_id');
        }else{
            return $this->belongsToMany(Option::class, 'option_parameter')->withPivotValue('parameter_id', $parameter_id);
        }
    }

    public function socials(): HasMany
    {
        return $this->hasMany(Social::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getNameAttribute()
    {
        if (is_null($this->getAttribute('short_name'))) {
            return $this->getAttribute('name');
        }
        return $this->getAttribute('short_name');
    }
}
