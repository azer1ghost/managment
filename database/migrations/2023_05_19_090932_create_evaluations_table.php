<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->integer('supplier_id');
            $table->integer('quality')->nullable();
            $table->integer('delivery')->nullable();
            $table->integer('distributor')->nullable();
            $table->integer('availability')->nullable();
            $table->integer('certificate')->nullable();
            $table->integer('support')->nullable();
            $table->integer('price')->nullable();
            $table->integer('payment')->nullable();
            $table->integer('returning')->nullable();
            $table->integer('replacement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
}
