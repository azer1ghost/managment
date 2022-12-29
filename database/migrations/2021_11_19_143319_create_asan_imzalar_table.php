<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsanImzalarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asan_imzalar', function (Blueprint $table) {
            $table->id();
            $table->string('asan_id')->nullable();
            $table->foreignId('user_id')->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('company_id')->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('department_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('pin1')->nullable();
            $table->string('pin2')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(1)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('asan_imzalar');
    }
}
