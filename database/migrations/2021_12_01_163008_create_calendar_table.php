<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarTable extends Migration
{
    public function up()
    {
        Schema::create('calendar', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->integer('type')->nullable();
            $table->foreignId('user_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->boolean('is_private')->default(0);
            $table->boolean('is_day_off')->default(0);
            $table->boolean('is_repeatable')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendar');
    }
}
