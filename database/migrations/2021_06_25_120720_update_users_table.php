<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname')->nullable();
            $table->string('father')->nullable();
            $table->string('serial')->nullable();
            $table->string('fin')->nullable();
            $table->date('birthday')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('email_coop')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_coop')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->integer('company_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
