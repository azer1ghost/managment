<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('class')->nullable();
            $table->json('title')->nullable();
            $table->json('detail')->nullable();
            $table->string('repeat_rate')->nullable();
            $table->boolean('status')->default(true);
            $table->text('permissions')->default('generally');
            $table->text('users')->nullable();
            $table->timestamp('will_notify_at')->nullable();
            $table->timestamp('will_end_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('announcements');
    }
}