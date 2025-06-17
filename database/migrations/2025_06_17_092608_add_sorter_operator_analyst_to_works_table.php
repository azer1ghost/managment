<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSorterOperatorAnalystToWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            if (!Schema::hasColumn('works', 'sorter_id')) {
                $table->foreignId('sorter_id')->nullable()->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('works', 'operator_id')) {
                $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('works', 'analyst_id')) {
                $table->foreignId('analyst_id')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropForeign(['sorter_id']);
            $table->dropForeign(['operator_id']);
            $table->dropForeign(['analyst_id']);

            $table->dropColumn(['sorter_id', 'operator_id', 'analyst_id']);
        });
    }
}
