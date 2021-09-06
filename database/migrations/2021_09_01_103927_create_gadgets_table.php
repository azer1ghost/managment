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
            $table->string('key');
            $table->json('name');
            $table->string('icon');
            $table->string('color');
            $table->string('bg_color');
            $table->string('detail');
            $table->string('html');
            $table->text('query');
            $table->integer('order');
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
