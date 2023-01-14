<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * @method static simplePaginate(int $int)
 * @method static create(array $validated)
 * @method static select(array $array)
 */
class Parameter extends Model implements Recordable
{
    use HasTranslations, SoftDeletes, \Altek\Accountant\Recordable, Eventually;

    public array $translatable = ['label', 'placeholder'];

    protected $fillable = ['name', 'label', 'placeholder', 'type', 'option_id', 'order', 'attributes'];

    // attributes which will be hidden or required to control POST and PUT requests on parameter usage
    // Ex: hideOnPost -> hide parameter which has hideOnPost attribute on POST request
    private array $parameterAttributes = ['hideOnPost'];

    public const PHONE = 8;
    public const CUSTOMER_ID = 6;

    public static function types(): array
    {
        return ['select' => 'Select', 'text' => 'Text', 'number' => 'Number'];
    }

    public function parameters(): HasMany
    {
        return $this->hasMany(__CLASS__);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class)->withDefault();
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)->withPivot('department_id');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_parameter');
    }

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class)->withPivot('company_id', 'department_id');
    }

    public function inquiries(): BelongsToMany
    {
        return $this->belongsToMany(Inquiry::class)->withPivot('value');
    }

    public function barcodes(): BelongsToMany
    {
        return $this->belongsToMany(Barcode::class)->withPivot('value');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_parameter')->withPivot('show_in_table', 'show_count');
    }

    public function satisfactions(): BelongsToMany
    {
        return $this->belongsToMany(Satisfaction::class, 'satisfaction_parameter')->withPivot('ordering');
    }

    public function works(): BelongsToMany
    {
        return $this->belongsToMany(Work::class, 'work_parameter')->withPivot('value');
    }
}
