<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_documents', function (Blueprint $table) {
            $table->id();
            $table->string('overhead_num')->nullable();
            $table->string('organization')->nullable();
            $table->string('content')->nullable();
            $table->string('note')->nullable();
            $table->dateTime('sent_date')->nullable();
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
        Schema::dropIfExists('sent_documents');
    }
}
