<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentIdColumnToCompanyParameterTable extends Migration
{
    public function up()
    {
        Schema::table('company_parameter', function (Blueprint $table) {
            $table->foreignId('department_id')->first()->nullable()->index()->constrained()->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::table('company_parameter', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });
    }
}
