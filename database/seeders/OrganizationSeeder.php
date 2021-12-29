<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{

    public function run()
    {
        Organization::factory(10)->create();

    }
}
