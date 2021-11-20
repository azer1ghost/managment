<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributesColumnToParametersTable extends Migration
{
    public function up()
    {
        Schema::table('parameters', function (Blueprint $table) {
            $table->text('attributes')->nullable();
        });
    }

    public function down()
    {
        Schema::table('parameters', function (Blueprint $table) {
            $table->dropColumn('attributes');
        });
    }
}
