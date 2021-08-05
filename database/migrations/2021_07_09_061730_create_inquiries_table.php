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
            $table->integer('subject')->nullable();
            $table->integer('kind')->nullable();
            $table->integer('source')->nullable();
            $table->integer('contact_method')->nullable();
            $table->integer('operation')->nullable();
            $table->integer('status')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->string('note')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->integer('redirected_user_id')->nullable();
            $table->foreignId('inquiry_id')->index()->nullable()->constrained()->onDelete('CASCADE');
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
