<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('custom_asan')->nullable();
            $table->string('custom_client')->nullable();
            $table->string('bank_charge')->nullable();
            $table->string('declaration_no')->nullable();
            $table->text('detail')->nullable();
            $table->integer('status')->nullable();
            $table->foreignId('creator_id')->constrained('users')->restrictOnDelete();
            $table->integer('payment_method')->nullable();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('department_id')->constrained()->restrictOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('datetime')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('vat_date')->nullable();
            $table->timestamp('invoiced_date')->nullable();
            $table->foreignId('asan_imza_id')->index()->nullable()->constrained('asan_imzalar')->onDelete('SET NULL');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('works');
    }
}
