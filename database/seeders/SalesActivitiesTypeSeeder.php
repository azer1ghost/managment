<?php

namespace Database\Seeders;

use App\Models\SalesActivityType;
use Illuminate\Database\Seeder;

class SalesActivitiesTypeSeeder extends Seeder
{
    public function run()
    {
        SalesActivityType::insert([
            [
                'name' => json_encode(['az' => 'Görüş', 'en' => 'Meeting']),
                'hard_columns' => '3,4,5'
            ],
            [
                'name' => json_encode(['az' => 'Seritifakat', 'en' => 'Certificate']),
                'hard_columns' => '1,2'
            ],
            [
                'name' => json_encode(['az' => 'Broker', 'en' => 'Broker']),
                'hard_columns' => '1'
            ],
            [
                'name' => json_encode(['az' => 'Tədbir', 'en' => 'Event']),
                'hard_columns' => '4'
            ]
        ]);
    }
}
