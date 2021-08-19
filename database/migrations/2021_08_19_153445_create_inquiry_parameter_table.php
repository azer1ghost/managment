<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiryParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_parameter', function (Blueprint $table) {
            $table->foreignId('inquiry_id')->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('parameter_id')->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('option_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->string('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        if (Schema::hasTable('inquiry_parameter')){
            Schema::table('inquiry_parameter', function (Blueprint $table) {
                $table->dropForeign(['inquiry_id']);
                $table->dropForeign(['option_id']);
                $table->dropForeign(['parameter_id']);
            });
        }
        Schema::dropIfExists('inquiry_parameter');
    }
}
