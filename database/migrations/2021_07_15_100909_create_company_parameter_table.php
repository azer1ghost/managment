<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_parameter', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('parameter_id')->nullable()->index()->constrained()->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        if (Schema::hasTable('company_parameter')){
            Schema::table('company_parameter', function (Blueprint $table) {
                $table->dropConstrainedForeignId('company_id');
                $table->dropConstrainedForeignId('parameter_id');
            });
        }
        Schema::dropIfExists('company_parameter');
    }
}
