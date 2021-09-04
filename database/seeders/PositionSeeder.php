<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run()
    {
        Position::insert([
            array(
                'name' => json_encode(['en' => 'Back end developer']),
                'role_id' => 1,
                'department_id' => 3,
                'permissions' => null,
            ),
            array(
                'name' => json_encode(['en' => 'Snr. Back end developer']),
                'role_id' => 1,
                'department_id' => 3,
                'permissions' => null,
            ),
            array(
                'name' => json_encode(['en' => 'Specialist']),
                'role_id' => 3,
                'department_id' => 2,
                'permissions' => null,
            ),
            array(
                'name' => json_encode(['en' => 'Packaging specialist']),
                'role_id' => 3,
                'department_id' => 2,
                'permissions' => null,
            ),
            array(
                'name' => json_encode(['en' => 'Call center director']),
                'role_id' => 3,
                'department_id' => 2,
                'permissions' => 'viewAll-inquiry'
            ),
        ]);
    }
}
