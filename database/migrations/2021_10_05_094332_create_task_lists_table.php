<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskListsTable extends Migration
{
    public function up()
    {
        Schema::create('task_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->integer('parent_task_id')->nullable();
            $table->string('name');
            $table->boolean('is_checked')->default(false);
            $table->foreignId('user_id')->nullable()->index()->constrained()->onDelete('SET NULL');
            $table->bigInteger('last_checked_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_lists');
    }
}