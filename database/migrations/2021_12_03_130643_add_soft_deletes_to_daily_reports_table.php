<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToDailyReportsTable extends Migration
{
    public function up()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
