<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->date('date')->useCurrent();
            $table->time('time')->useCurrent();
            $table->string('client')->nullable();
            $table->string('fullname')->nullable();
            $table->string('phone')->nullable();
            $table->integer('subject_id')->nullable();
            $table->integer('kind_id')->nullable();
            $table->integer('source_id')->nullable();
            $table->integer('contact_method_id')->nullable();
            $table->integer('operation_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->string('note')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->integer('redirected_user_id');
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
        Schema::dropIfExists('inquiries');
    }
}
