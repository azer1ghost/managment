<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaskIdColumnToTaskListsTable extends Migration
{
    public function up()
    {
        Schema::table('task_lists', function (Blueprint $table) {
            $table->integer('parent_task_id')->after('task_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('task_lists', function (Blueprint $table) {
            $table->dropColumn('parent_task_id');
        });
    }
}
