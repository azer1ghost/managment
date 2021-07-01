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
                'permissions' => serialize(['general','dashboard', 'account', 'signature']),
            ),
            array(
                'name' => "Employer",
                'key' => "employer",
                'permissions' => serialize(['general', 'dashboard', 'account', 'signature']),
            ),
        ]);
    }
}
