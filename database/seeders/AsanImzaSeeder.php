<?php

namespace Database\Seeders;

use App\Models\AsanImza;
use Illuminate\Database\Seeder;

class AsanImzaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AsanImza::factory()->count(10)->create();
    }
}
