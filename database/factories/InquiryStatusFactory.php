<?php

namespace Database\Factories;

use App\Models\Inquiry\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class InquiryStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Status::class;

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
