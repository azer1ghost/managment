<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->index()->nullable()->constrained('updates')->onDelete('SET NULL');
            $table->string('name')->nullable();
            $table->text('content')->nullable();
            $table->foreignId('user_id')->index()->nullable()->constrained()->onDelete('SET NULL');
            $table->integer('status')->nullable();
            $table->date('datetime')->nullable();
            $table->timestamp('done_at')->nullable();
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
        Schema::dropIfExists('updates');
    }
}
