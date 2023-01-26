<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Satisfaction extends Model
{
    protected $fillable = ['company_id', 'is_active', 'url'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class, 'satisfaction_parameter', 'satisfaction_id');
    }

    public function works(): HasMany
    {
        return $this->hasMany(CustomerSatisfaction::class);
    }

    public static function satisfactionParameters()
    {
        if (\Cache::has('satisfactionParameters')) {
            return \Cache::get('satisfactionParameters');
        }

        return \Cache::rememberForever('satisfactionParameters', function (){
            $data = [];
            foreach (collect(DB::table('satisfaction_parameter')->select('parameter_id')->get())->unique('parameter_id')->toArray() as $param){
                $data[] = [
                    'data' => Parameter::findOrFail($param->parameter_id),
                ];
            }

            return $data;
        });
    }
}
