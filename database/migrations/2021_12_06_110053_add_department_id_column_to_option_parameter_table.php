<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentIdColumnToOptionParameterTable extends Migration
{
    public function up()
    {
        Schema::table('option_parameter', function (Blueprint $table) {
            $table->foreignId('department_id')->after('parameter_id')->nullable()->index()->constrained()->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::table('option_parameter', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });
    }
}
