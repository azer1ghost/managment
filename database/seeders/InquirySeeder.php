<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function run()
    {
         Inquiry::factory(100)->create()->each(
            function ($inquiry) {
                $inquiry->parameters()->syncWithoutDetaching([1 => ['value' => rand(1, 3)]]);
                $inquiry->parameters()->syncWithoutDetaching([2 => ['value' => rand(10, 18)]]);
                $inquiry->parameters()->syncWithoutDetaching([3 => ['value' => rand(5, 9)]]);
                $inquiry->parameters()->syncWithoutDetaching([5 => ['value' => rand(21, 25)]]);
                $inquiry->parameters()->syncWithoutDetaching([6 => ['value' => "MBX".rand(6565, 999999)]]);
                $inquiry->parameters()->syncWithoutDetaching([7 => ['value' => $this->faker->firstName()]]);
            }
        );
    }
}
