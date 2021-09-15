<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskViewerTable extends Migration
{
    public function up()
    {
        Schema::create('task_viewer', function (Blueprint $table) {
            $table->foreignId('user_id')->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('task_id')->index()->constrained()->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_viewer');
    }
}