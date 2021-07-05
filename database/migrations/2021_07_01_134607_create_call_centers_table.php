<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_centers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->string('client');
            $table->string('fullname');
            $table->string('phone');
            $table->string('subject');
            $table->string('source');
            $table->string('note');
            $table->string('redirected');
            $table->boolean('status');
            $table->integer('user_id');
            $table->integer('company_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_centers');
    }
}
