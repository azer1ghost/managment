<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'detail' => $this->faker->text(),
            ];
    }
}
