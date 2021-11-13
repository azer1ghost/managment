<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->nullable();
            $table->string('father')->nullable();
            $table->string('gender')->nullable();
            $table->string('serial_pattern')->nullable();
            $table->string('serial')->nullable();
            $table->string('fin')->nullable();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('voen')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}