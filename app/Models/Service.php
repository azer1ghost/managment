<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasMany,
    SoftDeletes};
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ['name', 'detail', 'icon', 'company_id', 'department_id', 'service_id', 'has_asan_imza'];

    public array $translatable = ['name'];

    protected $casts = ['has_asan_imza' => 'boolean'];

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
        return $this->belongsToMany(Parameter::class, 'service_parameter')->withPivot('show_in_table', 'show_count');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'service_id')->withDefault();
    }

    public function services(): HasMany
    {
        return $this->hasMany(__CLASS__, 'service_id');
    }

    public static function serviceParameters()
    {
        $data = [];
        foreach (collect(DB::table('service_parameter')->latest('show_count')->select('parameter_id', 'show_count')->where('show_in_table', 1)->get())->unique('parameter_id')->toArray() as $param){
            $data[] = [
                'data' => Parameter::findOrFail($param->parameter_id),
                'count' => (bool) $param->show_count
            ];
        }

        return $data;
    }
}
