<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToBranchCashItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_cash_items', function (Blueprint $table) {
            $table->tinyInteger('payment_method')->nullable()->after('direction'); // 1=Nəğd, 2=Bank, 3=PBank
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_cash_items', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
}
