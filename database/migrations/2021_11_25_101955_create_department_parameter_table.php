<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentParameterTable extends Migration
{
    public function up()
    {
        Schema::create('department_parameter', function (Blueprint $table) {
            $table->foreignId('department_id')->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('parameter_id')->index()->constrained()->onDelete('CASCADE');
        });
    }

    public function down()
    {
        if (Schema::hasTable('department_parameter')){
            Schema::table('department_parameter', function (Blueprint $table) {
                $table->dropForeign(['department_id']);
                $table->dropForeign(['parameter_id']);
            });
        }
        Schema::dropIfExists('department_parameter');
    }
}
