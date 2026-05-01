<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_department_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('department_id');
            $table->string('main_paper')->nullable();
            $table->string('qibmain_paper')->nullable();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('CASCADE');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('CASCADE');
            $table->unique(['client_id', 'department_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_department_prices');
    }
};
