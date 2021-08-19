<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParameterOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameter_option', function (Blueprint $table) {
            $table->foreignId('option_id')->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('parameter_id')->index()->constrained()->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parameter_option');
    }
}
