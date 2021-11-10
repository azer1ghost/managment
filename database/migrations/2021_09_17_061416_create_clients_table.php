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
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('father')->nullable();
            $table->string('gender')->nullable();
            $table->string('serial_pattern')->nullable();
            $table->string('serial')->nullable();
            $table->string('fin')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('email_coop')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('phone_coop')->nullable();
            $table->string('voen')->nullable();
            $table->string('address')->nullable();
            $table->string('address_coop')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}