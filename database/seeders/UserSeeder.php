<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'name' => 'Azer',
            'surname' => 'Mamedov',
            'email' => 'mamedovazer124@gmail.com',
            'password' => Hash::make('4145124azer'),
            'phone' => '+994553791039',
            'role_id' => 1,
        ]);
    }
}
