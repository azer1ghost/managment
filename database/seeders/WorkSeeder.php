<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use App\Models\Work;
use Illuminate\Database\Seeder;

class WorkSeeder extends Seeder
{
    public function run()
    {
        Work::factory(10)->create();

    }
}
