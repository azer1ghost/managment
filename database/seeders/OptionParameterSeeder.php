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
        $parameter = Parameter::all();
        $ids = Option::pluck('id');
        $parameter->options()->sync($ids, ['company_id' => 4]);
    }
}
