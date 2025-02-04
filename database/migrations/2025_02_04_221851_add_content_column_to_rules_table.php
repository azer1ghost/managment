<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContentColumnToRulesTable extends Migration
{
    public function up()
    {
        Schema::table('rules', function (Blueprint $table) {
            $table->text('content')->nullable();
        });
    }

    public function down()
    {
        Schema::table('rules', function (Blueprint $table) {
           $table->dropColumn('content');
        });
    }
}
