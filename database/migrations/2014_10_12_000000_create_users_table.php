<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('avatar')->nullable();
            $table->string('gender')->nullable();
            $table->string('father')->nullable();
            $table->string('serial_pattern')->nullable();
            $table->string('serial')->nullable();
            $table->string('fin')->nullable();
            $table->date('birthday')->nullable();
            $table->string('position')->nullable();
            $table->string('department_id')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('email_coop')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('phone_coop')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('role_id')->default(2);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->integer('verify_code')->nullable();
            $table->string('password');
            $table->string('personal_id')->nullable()->unique();
            $table->string('position_id')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
