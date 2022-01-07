<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesClientFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'user_id' => User::inRandomOrder()->pluck('id')->first(),
            'phone' => $this->faker->phoneNumber,
            'voen' => rand(1000000, 9999999),
            'detail' => $this->faker->text('50'),
        ];
    }
}
