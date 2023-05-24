<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('voen')->nullable();
            $table->string('hn')->nullable();
            $table->string('mh')->nullable();
            $table->string('code')->nullable();
            $table->string('bank')->nullable();
            $table->string('bvoen')->nullable();
            $table->string('swift')->nullable();
            $table->string('orderer')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finance_clients');
    }
}
