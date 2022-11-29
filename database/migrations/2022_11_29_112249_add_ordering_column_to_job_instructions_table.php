<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderingColumnToJobInstructionsTable extends Migration
{
    public function up()
    {
        Schema::table('job_instructions', function (Blueprint $table) {
            $table->integer('ordering')->nullable();
        });
    }

    public function down()
    {
        Schema::table('job_instructions', function (Blueprint $table) {
            $table->dropColumn('ordering');
        });
    }
}
