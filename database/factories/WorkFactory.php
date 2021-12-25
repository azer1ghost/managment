<?php

namespace Database\Factories;

use App\Models\AsanImza;
use App\Models\Client;
use App\Models\Department;
use App\Models\Inquiry;
use App\Models\Service;
use App\Models\Work;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;

class WorkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Work::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'code' => Work::generateCustomCode(),
            'status' => rand(1,4),
            'detail' => $this->faker->text('160'),
            'user_id' => User::inRandomOrder()->pluck('id')->first(),
            'creator_id' => User::inRandomOrder()->pluck('id')->first(),
            'department_id' => Department::inRandomOrder()->pluck('id')->first(),
            'service_id' => Service::inRandomOrder()->pluck('id')->first(),
            'client_id' => Client::inRandomOrder()->pluck('id')->first(),
            'asan_imza_id' => AsanImza::inRandomOrder()->pluck('id')->first(),
        ];
    }
}
