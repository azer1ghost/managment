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
            $table->foreignId('company_id')->nullable()->index()->constrained()->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('option_parameter')){
            Schema::table('option_parameter', function (Blueprint $table) {
                $table->dropForeign(['company_id']);
                $table->dropForeign(['option_id']);
                $table->dropForeign(['parameter_id']);
            });
        }
        Schema::dropIfExists('option_parameter');
    }
}
