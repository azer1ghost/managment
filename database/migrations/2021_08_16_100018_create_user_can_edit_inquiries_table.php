<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCanEditInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_can_edit_inquiries', function (Blueprint $table) {
            $table->foreignId('user_id')->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('inquiry_id')->index()->constrained()->onDelete('CASCADE');
            $table->dateTime('editable_ended_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_can_edit_inquiries');
    }
}
