<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateUserDevicesTable extends Migration
{
    public function up()
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->string('device_key')->index()->nullable();
            $table->string('device')->nullable();
            $table->string('fcm_token')->nullable();
            $table->ipAddress('ip')->index()->nullable();
            $table->json('location')->nullable();
            $table->timestamps();
            $table->timestamp('last_active_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fcm_user_tokens');
    }
}
