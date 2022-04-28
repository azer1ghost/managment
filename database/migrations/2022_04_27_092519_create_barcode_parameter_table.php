<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodeParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcode_parameter', function (Blueprint $table) {
            $table->foreignId('barcode_id')->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('parameter_id')->index()->constrained()->onDelete('CASCADE');
            $table->string('value')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('barcode_parameter')){
            Schema::table('barcode_parameter', function (Blueprint $table) {
                $table->dropForeign(['barcode_id']);
                $table->dropForeign(['parameter_id']);
            });
        }
        Schema::dropIfExists('barcode_parameter');
    }
}
