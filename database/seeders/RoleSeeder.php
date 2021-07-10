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
        $permissions = implode(',',config('auth.permissions'));

        Role::insert([
            array(
                'name' => "Developer",
                'key' => "developer",
                'permissions' => 'all',
            ),
            array(
                'name' => "President",
                'key' => "president",
                'permissions' => 'all',
            ),
            array(
                'name' => "Call center operator",
                'key' => "customer-service-operator",
                'permissions' => $permissions,
            ),
            array(
                'name' => "Employer",
                'key' => "employer",
                'permissions' => $permissions,
            ),
        ]);
    }
}
