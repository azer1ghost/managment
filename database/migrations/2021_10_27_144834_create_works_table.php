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
            $table->integer('status')->nullable();
            $table->text('detail')->nullable();
            $table->integer('creator_id')->nullable();
            $table->foreignId('user_id')->index()->nullable()->constrained()->onDelete('SET NULL');
            $table->foreignId('department_id')->index()->nullable()->constrained()->onDelete('SET NULL');
            $table->foreignId('service_id')->index()->nullable()->constrained()->onDelete('SET NULL');
            $table->foreignId('client_id')->index()->nullable()->constrained()->onDelete('SET NULL');
            $table->timestamp('datetime')->nullable();
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
