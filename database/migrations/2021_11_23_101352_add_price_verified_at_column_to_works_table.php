<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceVerifiedAtColumnToWorksTable extends Migration
{
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->timestamp('price_verified_at')->after('verified_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn('price_verified_at');
        });
    }
}
