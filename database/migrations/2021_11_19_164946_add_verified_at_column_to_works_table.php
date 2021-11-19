<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerifiedAtColumnToWorksTable extends Migration
{
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->timestamp('verified_at')->after('done_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn('verified_at');
        });
    }
}
