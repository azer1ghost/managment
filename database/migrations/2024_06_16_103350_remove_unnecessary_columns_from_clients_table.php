<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnnecessaryColumnsFromClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('address2');
            $table->dropColumn('phone2');
            $table->dropColumn('email2');
            $table->dropColumn('phone3');
            $table->dropColumn('father');
            $table->dropColumn('satisfaction');
            $table->dropColumn('fin');
            $table->dropColumn('serial');
            $table->dropColumn('serial_pattern');
            $table->dropColumn('gender');
            $table->dropColumn('celebrate_at');
            $table->dropColumn('reference');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
