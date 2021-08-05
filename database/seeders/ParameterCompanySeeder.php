<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Parameter;
use Illuminate\Database\Seeder;

class ParameterCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::find(4);
        $ids = Parameter::pluck('id');
        $company->parameters()->sync($ids);
    }
}
