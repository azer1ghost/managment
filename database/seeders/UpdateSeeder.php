<?php

namespace Database\Seeders;

use App\Models\Update;
use Illuminate\Database\Seeder;

class UpdateSeeder extends Seeder
{

    public function run()
    {
        Update::factory(30)->create();
    }
}
