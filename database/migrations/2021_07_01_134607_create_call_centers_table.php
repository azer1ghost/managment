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
            $table->date('date')->useCurrent();
            $table->time('time')->useCurrent();
            $table->string('client')->nullable();
            $table->string('fullname')->nullable();
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->string('kinds')->nullable();
            $table->string('source');
            $table->string('note')->nullable();
            $table->string('redirected')->nullable();
            $table->string('status');
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
