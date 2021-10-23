<?php

namespace Database\Factories;

use App\Models\Update;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;

class UpdateFactory extends Factory
{
    protected $model = Update::class;

    public function definition()
    {
        return [
            'name' => $this->faker->text('20'),
            'content' => $this->faker->text('20'),
            'user_id' => User::inRandomOrder()->pluck('id')->first(),
            'status'  => rand(1, 9),
            'parent_id' => rand(1, 9),
            'datetime' => Carbon::now()->startOfMonth()->addDays(rand(1, 23)),
        ];
    }
}
