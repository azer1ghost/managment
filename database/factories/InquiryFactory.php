<?php

namespace Database\Factories;

use App\Models\Inquiry;
use App\Models\SalesClient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Auth\User;

class InquiryFactory extends Factory
{
    protected $model = Inquiry::class;

    public function definition(): array
    {
        return [
            'code' => Inquiry::generateCustomCode(),
            'datetime' => Carbon::now()->startOfMonth()->addDays(rand(1, 10)),
            'note' => $this->faker->realText(),
            'redirected_user_id' => User::inRandomOrder()->pluck('id')->first(),
            'company_id' => 4,
            'client_id' => SalesClient::inRandomOrder()->pluck('id')->first(),
            'client_relation_type' => 'SalesClient',
            'user_id' => User::inRandomOrder()->pluck('id')->first(),
            'is_out' => rand(0, 1)
        ];
    }
}
