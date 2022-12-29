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
            $table->foreignId('department_id')->nullable()->index()->constrained()->onDelete('SET NULL');
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->text('note')->nullable();
            $table->foreignId('user_id')->index()->nullable();
            $table->integer('redirected_user_id')->nullable();
            $table->foreignId('inquiry_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->string('client_id')->nullable();
            $table->boolean('is_out')->default(false);
            $table->timestamp('next_call_at')->nullable();
            $table->boolean('checking')->nullable()->default(0);
            $table->boolean('notified')->default(0);
            $table->timestamp('alarm')->nullable();
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
