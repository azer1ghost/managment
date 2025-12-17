<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixNullPaymentParametersInWorkParameterTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * Fixes NULL values in payment-related work_parameter records.
     * Payment parameters (33,34,35,36,37,38,48,50,55) must never be NULL
     * to prevent arithmetic errors in payment calculations.
     *
     * @return void
     */
    public function up()
    {
        // Payment-related parameter IDs that must never be NULL
        // 33 = AMOUNT (base amount)
        // 34 = VAT
        // 35 = PAID (paid base)
        // 36 = VATPAYMENT (paid VAT)
        // 37 = ILLEGALPAID
        // 38 = ILLEGALAMOUNT
        // 48 = MAINPAGE (used in calculations)
        // 50 = QIBPAYMENT
        // 55 = QIBAMOUNT
        $paymentParameterIds = [33, 34, 35, 36, 37, 38, 48, 50, 55];
        
        // Update all NULL values to '0' for payment-related parameters
        DB::table('work_parameter')
            ->whereIn('parameter_id', $paymentParameterIds)
            ->whereNull('value')
            ->update(['value' => '0']);
        
        // Also handle empty strings as NULL (normalize them to '0')
        DB::table('work_parameter')
            ->whereIn('parameter_id', $paymentParameterIds)
            ->where('value', '')
            ->update(['value' => '0']);
    }

    /**
     * Reverse the migrations.
     * 
     * Note: This migration cannot be fully reversed as we don't know
     * which values were originally NULL vs which were actually 0.
     * However, setting them back to NULL would break the application,
     * so we leave them as 0.
     *
     * @return void
     */
    public function down()
    {
        // Cannot safely reverse - we don't know which values were originally NULL
        // Leaving them as 0 is safer than setting back to NULL
        // This migration is designed to be a one-time data fix
    }
}
