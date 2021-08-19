<?php

namespace Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Seeder;

class ParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Parameter::insert([
            ['type' => 'select', 'name' =>  json_encode(['en' => 'Subject', 'az' => 'Mövzusu'])],
            ['type' => 'select', 'name' =>  json_encode(['en' => 'Kind', 'az' => 'Növü'])]
        ]);
    }
}
