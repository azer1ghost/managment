<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeColumnToWorksTable extends Migration
{
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->string('code')->after('id')->nullable()->unique();
        });
    }
    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
