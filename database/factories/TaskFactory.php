<?php

namespace Database\Factories;

use App\Models\Inquiry;
use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text('20'),
            'note' => $this->faker->realText(),
            'user_id' => User::inRandomOrder()->pluck('id')->first(),
        ];
    }
}
