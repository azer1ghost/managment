<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_parameter', function (Blueprint $table) {
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
        Schema::dropIfExists('option_parameter');
    }
}
