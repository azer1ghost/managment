<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkParameterTable extends Migration
{
    public function up()
    {
        Schema::create('work_parameter', function (Blueprint $table) {
            $table->foreignId('work_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('parameter_id')->constrained()->restrictOnDelete();
            $table->string('value')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_parameter');
    }
}
