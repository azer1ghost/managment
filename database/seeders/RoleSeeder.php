<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            array(
                'name' => "Developer",
                'key' => "developer",
                'permissions' => serialize(config('auth.permissions')),
            ),
            array(
                'name' => "Call center operator",
                'key' => "call-center-operator",
                'permissions' => serialize(config('auth.permissions')),
            ),
            array(
                'name' => "Employer",
                'key' => "employer",
                'permissions' => serialize(config('auth.permissions')),
            ),
        ]);
    }
}
