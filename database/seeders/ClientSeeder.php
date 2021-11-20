<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{

    public function run()
    {
        Client::factory(10)->create();
    }
}
