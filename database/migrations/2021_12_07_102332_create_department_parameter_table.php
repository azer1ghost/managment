<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentParameterTable extends Migration
{
    public function up()
    {
        Schema::create('department_parameter', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('parameter_id')->nullable()->index()->constrained()->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('department_parameter');
    }
}
