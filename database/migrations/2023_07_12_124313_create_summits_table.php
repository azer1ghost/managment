<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummitsTable extends Migration
{
    public function up()
    {
        Schema::create('summits', function (Blueprint $table) {
            $table->id();
            $table->string('event')->nullable();
            $table->string('place')->nullable();
            $table->string('dresscode')->nullable();
            $table->integer('status')->nullable();
            $table->dateTime('date')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('summits');
    }
}
