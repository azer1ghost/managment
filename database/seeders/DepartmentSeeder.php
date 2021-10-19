<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::insert([
            [
                'name' => json_encode(['en' => 'Marketing' , 'az' => 'Marketinq']),
                'status' => true
            ],
            [
                'name' => json_encode(['en' => 'Call center' , 'az' => 'Çağrı mərkəzi']),
                'status' => true
            ],
            [
                'name' => json_encode(['en' => 'Information technology' , 'az' => 'İnformasiya texnologiyaları']),
                'status' => true
            ],
            [
                'name' => json_encode(['en' => 'Human resources' , 'az' => 'İnsan resursları']),
                'status' => true
            ],
        ]);
    }
}
