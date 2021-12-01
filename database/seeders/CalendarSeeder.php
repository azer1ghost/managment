<?php

namespace Database\Seeders;

use App\Models\Calendar;
use Illuminate\Database\Seeder;

class CalendarSeeder extends Seeder
{
    public function run()
    {
        Calendar::insert([
            [
                'date' => [now()->subDay(), now()->subDays(2), now()->subDays(3), now()->subDays(4)][rand(0, 3)],
                'is_day_off' => rand(0, 1),
            ],
            [
                'date' => [now()->subDay(), now()->subDays(2), now()->subDays(3), now()->subDays(4)][rand(0, 3)],
                'is_day_off' => rand(0, 1),
            ],
            [
                'date' => [now()->subDay(), now()->subDays(2), now()->subDays(3), now()->subDays(4)][rand(0, 3)],
                'is_day_off' => rand(0, 1),
            ],
            [
                'datetime' => [now()->subDay(), now()->subDays(2), now()->subDays(3), now()->subDays(4)][rand(0, 3)],
                'is_day_off' => rand(0, 1),
            ],
            [
                'date' => [now()->subDay(), now()->subDays(2), now()->subDays(3), now()->subDays(4)][rand(0, 3)],
                'is_day_off' => rand(0, 1),
            ],
        ]);
    }
}
