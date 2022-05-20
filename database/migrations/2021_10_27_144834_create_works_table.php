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
        Schema::dropIfExists('work_status_logs');

        Schema::dropIfExists('work_parameter');

        Schema::dropIfExists('works');

        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('custom_asan')->nullable();
            $table->text('detail')->nullable();
            $table->integer('status')->nullable();
            $table->foreignId('creator_id')->constrained('users')->restrictOnDelete();
            $table->integer('payment_method')->nullable();
            $table->foreignId('asan_imza_id')->constrained('asan_imzalar')->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('department_id')->constrained()->restrictOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('datetime')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('vat_date')->nullable();
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
