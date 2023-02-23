<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->nullable();
            $table->foreignId('position_id')->nullable();
            $table->string('composition')->nullable();
            $table->boolean('is_readonly')->nullable();
            $table->boolean('is_change')->nullable();
            $table->boolean('is_print')->nullable();
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
        Schema::dropIfExists('access_rates');
    }
}
