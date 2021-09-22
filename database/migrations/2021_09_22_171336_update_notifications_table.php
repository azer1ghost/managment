<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('provider')->nullable();
            $table->string('channel')->nullable();
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            //
        });
    }
}