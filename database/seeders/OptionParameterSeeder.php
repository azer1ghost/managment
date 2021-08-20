<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Parameter;
use Illuminate\Database\Seeder;

class OptionParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add options to Subject parameter
        Parameter::find(1)->options()->syncWithPivotValues(
            Option::whereIn('id', [1,2,3])->pluck('id'), ['company_id' => 4]
        );

        // Add options to Kind parameter
        Parameter::find(2)->options()->syncWithPivotValues(
            Option::whereIn('id', range(10, 18))->pluck('id'), ['company_id' => 4]
        );

        // Add options to Source parameter
        Parameter::find(3)->options()->syncWithPivotValues(
            Option::whereIn('id', range(5, 9))->pluck('id'), ['company_id' => 4]
        );

        // Add options to Contact Method parameter
        Parameter::find(4)->options()->syncWithPivotValues(
            Option::whereIn('id', range(4, 9))->pluck('id'), ['company_id' => 4]
        );

        // Add options to Status parameter
        Parameter::find(5)->options()->syncWithPivotValues(
            Option::whereIn('id', range(22, 25))->pluck('id'), ['company_id' => 4]
        );
    }
}
