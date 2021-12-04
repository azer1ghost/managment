<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAsanImzaIdColumnToWorksTable extends Migration
{
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->foreignId('asan_imza_id')->after('client_id')->index()->nullable()->constrained('asan_imzalar')->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropConstrainedForeignId('asan_imza_id');
        });
    }
}
