<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('performer')->nullable();
            $table->integer('receiver')->nullable();
            $table->string('sender');
            $table->string('number')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('arrived_at')->nullable();
            $table->dateTime('received_at')->nullable();
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
        Schema::dropIfExists('registration_logs');
    }
}
