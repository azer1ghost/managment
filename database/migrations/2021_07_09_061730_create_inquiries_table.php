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
            $table->string('code')->nullable()->unique();
            $table->dateTime('datetime')->index()->useCurrent();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->string('note')->nullable();
            $table->foreignId('user_id')->index()->nullable();
            $table->integer('redirected_user_id')->nullable();
            $table->foreignId('inquiry_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->boolean('is_out')->default(false);
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
