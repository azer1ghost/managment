<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'detail' => $this->faker->text(150),
            'organization_id' => Organization::inRandomOrder()->first()->id,
        ];
    }
}
