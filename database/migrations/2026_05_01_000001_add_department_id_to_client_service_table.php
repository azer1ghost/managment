<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_service', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('service_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('SET NULL');
        });
    }

    public function down(): void
    {
        Schema::table('client_service', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
