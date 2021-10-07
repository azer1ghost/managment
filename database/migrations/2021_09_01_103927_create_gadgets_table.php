<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGadgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gadgets', function (Blueprint $table) {
            $table->id();
            $table->string('key')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->text('labels')->nullable();
            $table->text('colors')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->string('bg_color')->nullable();
            $table->string('details')->nullable();
            $table->text('query')->nullable();
            $table->integer('order')->nullable();
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('gadgets');
    }
}
