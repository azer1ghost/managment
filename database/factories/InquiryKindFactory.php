<?php

namespace Database\Factories;

use App\Models\Inquiry\Kind;
use Illuminate\Database\Eloquent\Factories\Factory;

class InquiryKindFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kind::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}
