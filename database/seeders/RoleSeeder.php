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
                'name' => json_encode(['en' => 'Developer']),
                'key' => "developer",
                'permissions' => 'all',
            ),
            array(
                'name' => json_encode(['en' => 'President']),
                'key' => "president",
                'permissions' => 'all',
            ),
            array(
                'name' => json_encode(['en' => 'Call center operator']),
                'key' => "call-center-operator",
                'permissions' => $permissions,
            ),
            array(
                'name' => json_encode(['en' => 'Employer']),
                'key' => "employer",
                'permissions' => $permissions,
            ),
        ]);
    }
}
