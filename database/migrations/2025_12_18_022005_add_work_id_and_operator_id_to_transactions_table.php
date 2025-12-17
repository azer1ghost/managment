<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkIdAndOperatorIdToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('work_id')->nullable()->after('client_id')->constrained('works')->onDelete('cascade');
            $table->foreignId('operator_id')->nullable()->after('work_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['work_id']);
            $table->dropForeign(['operator_id']);
            $table->dropColumn(['work_id', 'operator_id']);
        });
    }
}
