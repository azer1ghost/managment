<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSatisfactionParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_satisfaction_parameter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_satisfaction_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('parameter_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->integer('ordering')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_satisfaction_parameter');
    }
}
