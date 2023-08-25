<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics', function (Blueprint $table) {
            $table->id();
            $table->string('reg_number')->nullable();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->integer('reference_id')->nullable();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->integer('client_id')->nullable();
            $table->string('status')->nullable();
            $table->integer('number')->nullable();
            $table->timestamp('datetime')->nullable();
            $table->timestamp('paid_at')->nullable();
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
        Schema::dropIfExists('logistics');
    }
}
