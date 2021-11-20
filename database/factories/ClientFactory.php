<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{

    function generateRandomFin($length = 7){
        return substr(str_shuffle(str_repeat($x='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    public function definition()
    {

        return [
            'fullname' => $this->faker->name(),
            'father'   => $this->faker->name(),
            'gender' => rand(0,1),
            'serial_pattern' => array('AZE','AA')[rand(0,1)],
            'serial' => rand(7),
            'fin'  => $this->generateRandomFin(),
            'phone2' => $this->faker->phoneNumber,
            'phone1' => $this->faker->phoneNumber ,
            'email2' => $this->faker->unique()->safeEmail(),
            'email1' => $this->faker->unique()->safeEmail(),
            'address1' => $this->faker->address,
            'address2' => $this->faker->address,
            'voen' => rand(7),
            'position' => 'CEO',
            'type' => rand(0,1),
            'detail' => $this->faker->text('50'),
        ];
    }
}
