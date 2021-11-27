<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowInTableColumnToServiceParameterTable extends Migration
{
    public function up()
    {
        Schema::table('service_parameter', function (Blueprint $table) {
            $table->boolean('show_in_table');
        });
    }

    public function down()
    {
        Schema::table('service_parameter', function (Blueprint $table) {
            $table->dropColumn('show_in_table');
        });
    }
}
