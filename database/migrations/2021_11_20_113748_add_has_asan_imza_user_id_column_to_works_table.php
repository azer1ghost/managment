<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasAsanImzaUserIdColumnToWorksTable extends Migration
{

    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->integer('asan_imza_id')->after('client_id')->index()->nullable();
        });
    }

    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn('asan_imza_user_id');
        });
    }
}
