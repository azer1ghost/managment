<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static select(string[] $array)
 * @method static create(array $validated)
 * @method static find($selectedCompany)
 */
class Company extends Model implements Recordable
{
    use SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    // MOBEX ID
    const MOBIL_EXPRESS = 4;
    const MOBIL_GROUP = 1;

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
        return $this->belongsToMany(Parameter::class)->withPivot('department_id');
    }

    public function options($parameter_id = null): BelongsToMany
    {
        if(is_null($parameter_id)){
            return $this->belongsToMany(Option::class, 'option_parameter')->withPivot('parameter_id', 'department_id');
        }else{
            return $this->belongsToMany(Option::class, 'option_parameter')->withPivot('department_id')->withPivotValue('parameter_id', $parameter_id);
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

    public function asanImzalar(): HasMany
    {
        return $this->hasMany(AsanImza::class);
    }
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'clients_companies_relationship');
    }
}
