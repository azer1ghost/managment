<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->integer('hard_level')->nullable();
            $table->double('earning', '8', '2')->nullable();
            $table->string('currency')->nullable();
            $table->double('currency_rate', '8', '2')->nullable();
            $table->integer('status')->nullable();
            $table->text('detail')->nullable();
            $table->integer('creator_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('SET NULL');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('CASCADE');
            $table->integer('client_id')->nullable();
            $table->timestamp('datetime')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('done_at')->nullable();
            $table->timestamp('verified_at')->nullable();
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
        Schema::dropIfExists('works');
    }
}
