<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('priority')->nullable();
            $table->string('note')->nullable();
            $table->string('status')->nullable();
            $table->morphs('taskable');
            $table->timestamp('must_start_at')->nullable();
            $table->timestamp('must_end_at')->nullable();
            $table->timestamp('done_at')->nullable();
            $table->integer('done_by_user_id')->nullable();
            $table->foreignId('user_id');
            $table->integer('inquiry_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
