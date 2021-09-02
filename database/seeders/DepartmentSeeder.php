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
                'name' => json_encode(['en' => 'Marketing' , 'az' => 'Marketinq'])
            ],
            [
                'name' => json_encode(['en' => 'Call center' , 'az' => 'Çağrı mərkəzi'])
            ],
            [
                'name' => json_encode(['en' => 'Information technology' , 'az' => 'İnformasiya texnologiyaları'])
            ],
            [
                'name' => json_encode(['en' => 'Human resources' , 'az' => 'İnsan resursları'])
            ],
        ]);
    }
}
