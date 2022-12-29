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
            $table->date('started_at')->nullable();
            $table->integer('position_id')->nullable();
            $table->integer('official_position_id')->nullable();
            $table->text('permissions')->nullable();
            $table->integer('department_id')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('email_coop')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('phone_coop')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('role_id')->default(4);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->integer('verify_code')->nullable();
            $table->string('password');
            $table->string('default_lang',5)->nullable();
            $table->integer('personal_id')->nullable()->unique();
            $table->boolean('is_partner')->default(false);
            $table->integer('order')->nullable()->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('disabled_at')->nullable();
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
