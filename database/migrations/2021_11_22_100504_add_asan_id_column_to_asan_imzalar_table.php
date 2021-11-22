<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAsanIdColumnToAsanImzalarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asan_imzalar', function (Blueprint $table) {
            $table->string('asan_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asan_imzalar', function (Blueprint $table) {
            $table->dropColumn('asan_id');
        });
    }
}
