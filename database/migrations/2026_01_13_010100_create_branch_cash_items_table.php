<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_cash_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_cash_id')->constrained('branch_cashes')->cascadeOnDelete();
            $table->foreignId('work_id')->nullable()->constrained('works')->nullOnDelete();
            $table->enum('direction', ['income', 'expense'])->default('income'); // mədaxil / məxaric
            $table->string('description')->nullable(); // Növ
            $table->integer('gb')->nullable();
            $table->integer('representative')->nullable(); // Təmsilçi
            $table->integer('sb')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('amount', 12, 2)->default(0); // Məbləğ
            $table->string('note')->nullable(); // Qeyd
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_cash_items');
    }
};

