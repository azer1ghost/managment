<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->string('voen');
            $table->string('main_activity')->nullable();
            $table->string('asan_imza');
            $table->string('code')->nullable();
            $table->string('adress')->nullable();
            $table->string('voen_code')->nullable();
            $table->string('voen_pass')->nullable();
            $table->string('pass')->nullable();
            $table->string('respublika_code')->nullable();
            $table->string('respublika_pass')->nullable();
            $table->string('kapital_code')->nullable();
            $table->string('kapital_pass')->nullable();
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
        Schema::dropIfExists('funds');
    }
}
