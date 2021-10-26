<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoneAtColumnToUpdatesTable extends Migration
{
    public function up()
    {
        Schema::table('updates', function (Blueprint $table) {
            $table->timestamp('done_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('updates', function (Blueprint $table) {
            $table->dropColumn('done_at');
        });
    }
}
