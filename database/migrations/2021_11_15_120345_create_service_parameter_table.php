<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceParameterTable extends Migration
{
    public function up()
    {
        Schema::create('service_parameter', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('parameter_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->integer('ordering')->nullable();
            $table->boolean('show_in_table');
            $table->boolean('show_count');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_parameter');
    }
}
