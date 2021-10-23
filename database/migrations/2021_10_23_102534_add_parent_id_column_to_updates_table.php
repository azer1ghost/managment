<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdColumnToUpdatesTable extends Migration
{
    public function up()
    {
        Schema::table('updates', function (Blueprint $table) {
            $table->integer('parent_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('updates', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
}
