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
            array(
                'name' => "Marketing",
            ),
            array(
                'name' => "Call center",
            ),
            array(
                'name' => "IT",
            ),
            array(
                'name' => "HR",
            ),
        ]);
    }
}
