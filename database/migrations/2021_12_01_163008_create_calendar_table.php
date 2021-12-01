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
            $table->timestamp('datetime');
            $table->boolean('is_day_off');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendar');
    }
}
