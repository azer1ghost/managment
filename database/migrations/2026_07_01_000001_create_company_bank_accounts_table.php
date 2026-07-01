<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('label');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('voen')->nullable();
            $table->string('hh')->nullable();
            $table->string('mh')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_kod')->nullable();
            $table->string('bank_voen')->nullable();
            $table->string('swift')->nullable();
            $table->string('who')->nullable();
            $table->string('who_footer')->nullable();
            $table->string('representer')->default('Gömrük Təmsilçisi');
            $table->string('stamp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_bank_accounts');
    }
};
