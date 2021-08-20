<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * @method static simplePaginate(int $int)
 * @method static create($validated)
 * @method static select(array $array)
 * @method static distinct()
 */
class Parameter extends Model
{
    use HasTranslations, SoftDeletes;

    public array $translatable = ['name'];

    protected $fillable = ['name', 'type', 'parameter_id'];

    public function parameters(): HasMany
    {
        return $this->hasMany(__CLASS__);
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class);
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class)->withPivot('company_id');
    }

    public function inquiries(): BelongsToMany
    {
        return $this->belongsToMany(Inquiry::class)->withPivot('option_id', 'value');
    }
}
