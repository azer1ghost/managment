<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogisticsParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics_parameter', function (Blueprint $table) {
            $table->foreignId('logistics_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('parameter_id')->constrained()->restrictOnDelete();
            $table->string('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logistics_parameter');
    }
}
