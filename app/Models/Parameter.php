<?php

namespace App\Models;

use App\Traits\Loger;
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
    use HasTranslations, SoftDeletes, Loger;

    public array $translatable = ['label', 'placeholder'];

    protected $fillable = ['name', 'label', 'placeholder', 'type', 'option_id', 'order'];

    public static function types(): array
    {
        return ['select' => 'Select', 'text' => 'Text', 'number' => 'Number'];
    }

    public static function attributes(): array
    {
        return ['min', 'max', 'step'];
    }

    public function parameters(): HasMany
    {
        return $this->hasMany(__CLASS__);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
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
        return $this->belongsToMany(Inquiry::class)->withPivot('value');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_parameter');
    }

    public function works(): BelongsToMany
    {
        return $this->belongsToMany(Work::class, 'work_parameter')->withPivot('value');
    }
}
