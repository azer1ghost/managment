<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run()
    {
        Comment::factory()->count(3)->for(
            Task::factory()->for(
                User::first(), 'taskable'
            ), 'commentable'
        )->create();

        Comment::factory()->count(3)->for(
            Comment::first(), 'commentable'
        )->create();
    }
}
