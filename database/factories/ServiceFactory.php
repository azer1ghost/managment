<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Department;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'icon' => 'fa-fa-trash',
            'detail' => $this->faker->text(50),
            'company_id' => Company::inRandomOrder()->first()->id,
            'department_id' => Department::inRandomOrder()->first()->id,
            'service_id' => rand(1,5),
            'has_asan_imza' => rand(0,1),
        ];
    }
}
