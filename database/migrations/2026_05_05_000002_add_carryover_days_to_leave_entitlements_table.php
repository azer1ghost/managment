<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCarryoverDaysToLeaveEntitlementsTable extends Migration
{
    public function up(): void
    {
        Schema::table('leave_entitlements', function (Blueprint $table) {
            $table->integer('carryover_days')->default(0)->after('extra_days')
                ->comment('Keçmiş illərdən qalan məzuniyyət günləri');
        });
    }

    public function down(): void
    {
        Schema::table('leave_entitlements', function (Blueprint $table) {
            $table->dropColumn('carryover_days');
        });
    }
}
