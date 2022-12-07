<?php

namespace Database\Factories;

use App\Models\AsanImza;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsanImzaFactory extends Factory
{
    protected $model = AsanImza::class;

    public function definition()
    {
        return [
            'company_id' => Company::inRandomOrder()->first()->id,
            'department_id' => Department::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'asan_id' => rand(1000, 9999),
            'phone' => $this->faker->phoneNumber,
            'pin1' => rand(1000, 9999),
            'pin2' => rand(1000, 9999)
        ];
    }
}
