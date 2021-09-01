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
            [
               'name' => 'Azer',
               'surname' => 'Mamedov',
               'email' => 'mamedovazer124@gmail.com',
               'email_coop' => 'mamedovazer124@gmail.com',
               'password' => Hash::make('4145124azer'),
               'phone' => '+994553791039',
               'role_id' => 1,
               'department_id' => 3,
               'company_id' => 1,
           ],
           [
               'name' => 'Test',
               'surname' => 'Test',
               'email' => 'test@mobilgroup.az',
               'email_coop' => 'test@mobilgroup.az',
               'password' => Hash::make('Aa123456'),
               'phone' => '+994553791039',
               'role_id' => 1,
               'department_id' => 3,
               'company_id' => 1,
           ]
        ]);
    }
}
