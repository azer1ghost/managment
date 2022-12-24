<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSatisfactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_satisfactions', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('activity')->nullable();
            $table->text('content')->nullable();
            $table->string('reason')->nullable();
            $table->string('result')->nullable();
            $table->boolean('is_enough')->nullable();
            $table->boolean('more_time')->nullable();
            $table->date('datetime')->nullable();
            $table->date('deadline')->nullable();
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
        Schema::dropIfExists('employee_satisfactions');
    }
}
