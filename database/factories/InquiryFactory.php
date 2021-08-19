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

    protected function generateCode($prefix = 'MG'): string
    {
        return $prefix.str_pad(random_int(0, 999999), 6, 0, STR_PAD_LEFT);
    }

    protected function createCode(): string
    {
        $code = $this->generateCode();
        return Inquiry::select('code')->where('code', $code)->exists() ? $this->createCode() : $code;
    }

    public function definition()
    {

       $subject = Parameter::where('key', 'subject')->inRandomOrder()->pluck('id')->first();
       $kind = Parameter::where('key', 'kind')->where('parameter_id', $subject)->inRandomOrder()->pluck('id')->first();



        return [
            'code' => $this->createCode(),
            'datetime' => now(),
            'client' => "MBX".random_int(55555, 99999),
            'fullname' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'subject' => $subject,
            'kind' => $kind,
            'source' => Parameter::where('key', 'source')->inRandomOrder()->pluck('id')->first(),
            'contact_method' => Parameter::where('key', 'contact_method')->inRandomOrder()->pluck('id')->first(),
            'operation' => Parameter::where('key', 'operation')->inRandomOrder()->pluck('id')->first(),
            'note' => $this->faker->realText(),
            'redirected_user_id' => User::inRandomOrder()->pluck('id')->first(),
            'status' => Parameter::where('key', 'status')->inRandomOrder()->pluck('id')->first(),
            'company_id' => 4,
            'user_id' => User::inRandomOrder()->pluck('id')->first(),
        ];
    }
}
