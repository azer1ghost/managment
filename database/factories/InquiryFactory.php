<?php

namespace Database\Factories;

use App\Models\Inquiry;
use App\Models\Parameter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;

class InquiryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Inquiry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {

       $subject = Parameter::where('type', 'subject')->inRandomOrder()->pluck('id')->first();
       $kind = Parameter::where('type', 'kind')->where('parameter_id', $subject)->inRandomOrder()->pluck('id')->first();

        return [
            'code' => "MG".random_int(555555, 999999),
            'datetime' => now(),
            'client' => "MBX".random_int(55555, 99999),
            'fullname' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'subject' => $subject,
            'kind' => $kind,
            'source' => Parameter::where('type', 'source')->inRandomOrder()->pluck('id')->first(),
            'contact_method' => Parameter::where('type', 'contact_method')->inRandomOrder()->pluck('id')->first(),
            'operation' => Parameter::where('type', 'operation')->inRandomOrder()->pluck('id')->first(),
            'note' => $this->faker->realText(),
            'redirected_user_id' => User::inRandomOrder()->pluck('id')->first(),
            'status' => Parameter::where('type', 'status')->inRandomOrder()->pluck('id')->first(),
            'company_id' => 4,
            'user_id' => User::inRandomOrder()->pluck('id')->first(),
        ];
    }
}
