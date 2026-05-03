<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logistics', function (Blueprint $table) {
            $table->string('origin_country')->nullable()->after('transport_type');
            $table->string('origin_city')->nullable()->after('origin_country');
            $table->string('destination_country')->nullable()->after('origin_city');
            $table->string('destination_city')->nullable()->after('destination_country');
            $table->foreignId('vendor_id')->nullable()->constrained('suppliers')->nullOnDelete()->after('destination_city');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->after('vendor_id');
            $table->enum('shipping_type', ['FTL', 'LTL', 'LCL', 'FCL', 'FTL_avia'])->nullable()->after('payment_status');
            $table->string('incoterms')->nullable()->after('shipping_type');
        });
    }

    public function down(): void
    {
        Schema::table('logistics', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn([
                'origin_country', 'origin_city', 'destination_country', 'destination_city',
                'vendor_id', 'payment_status', 'shipping_type', 'incoterms',
            ]);
        });
    }
};
