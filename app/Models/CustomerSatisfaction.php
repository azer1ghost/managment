<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class CustomerSatisfaction extends Model
{
    protected $fillable = ['company_id', 'is_active'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function parameters(): BelongsToMany
    {
        return $this->belongsToMany(Parameter::class, 'customer_satisfaction_parameter', 'customer_satisfaction_id')->withPivot('ordering');
    }

    public static function customerSatisfactionParameters()
    {
        if (\Cache::has('customerSatisfactionParameters')) {
            return \Cache::get('customerSatisfactionParameters');
        }

        return \Cache::rememberForever('customerSatisfactionParameters', function (){
            $data = [];
            foreach (collect(DB::table('customer_satisfaction_parameter')->select('parameter_id')->get())->unique('parameter_id')->toArray() as $param){
                $data[] = [
                    'data' => Parameter::findOrFail($param->parameter_id),
                ];
            }

            return $data;
        });
    }
}
