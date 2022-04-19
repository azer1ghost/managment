<?php

namespace App\Models;

use Altek\Accountant\Contracts\Recordable;
use Altek\Eventually\Eventually;
use Illuminate\Database\Eloquent\{Factories\HasFactory,
    Model,
    Relations\BelongsTo,
    Relations\BelongsToMany,
    Relations\HasMany,
    SoftDeletes};
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;
use Str;

class Service extends Model implements Recordable
{
    use HasFactory, SoftDeletes, HasTranslations, \Altek\Accountant\Recordable, Eventually;

    protected $fillable = ['name', 'detail', 'icon', 'company_id', 'department_id', 'service_id', 'has_asan_imza'];

    public array $translatable = ['name'];

    protected $casts = ['has_asan_imza' => 'boolean'];

    public function hasAsanImza()
    {
        return $this->getAttribute('has_asan_imza');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
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

    public function getShortNameAttribute()
    {
        $name = $this->getAttribute('name');

        if (str_word_count($name) > 1){
            $name = implode('',
                array_map(function($v) { return $v[0]; },
                    array_filter(
                        array_map('trim',explode(' ', Str::title($name)))
                    )
                )
            );
        }

        return $name;
    }

    public static function serviceParameters()
    {
        if (\Cache::has('serviceParameters')) {
            return \Cache::get('serviceParameters');
        }

        return \Cache::rememberForever('serviceParameters', function (){
            $data = [];
            foreach (collect(DB::table('service_parameter')->latest('show_count')->latest('show_in_table')->select('parameter_id', 'show_count')->where('show_in_table', 1)->get())->unique('parameter_id')->toArray() as $param){
                $data[] = [
                    'data' => Parameter::findOrFail($param->parameter_id),
                    'count' => (bool) $param->show_count
                ];
            }

            return $data;
        });
    }
    public static function serviceParametersExport()
    {
        if (\Cache::has('serviceParametersExport')) {
            return \Cache::get('serviceParametersExport');
        }

        return \Cache::rememberForever('serviceParametersExport', function (){
            $data = [];
            foreach (collect(DB::table('service_parameter')->select('parameter_id', 'show_count')->where('show_count', 1)->get())->unique('parameter_id')->toArray() as $param){
                $data[] = [
                    'data' => Parameter::findOrFail($param->parameter_id),
                    'count' => (bool) $param->show_count
                ];
            }

            return $data;
        });
    }
}
