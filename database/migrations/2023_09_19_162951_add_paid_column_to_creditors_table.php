<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidColumnToCreditorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('creditors', function (Blueprint $table) {
            $table->string('paid')->after('amount')->nullable();
            $table->string('vat_paid')->after('vat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('creditors', function (Blueprint $table) {
            //
        });
    }
}
