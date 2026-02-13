<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTelegramToTransitCustomersTable extends Migration
{
    public function up()
    {
        Schema::table('transit_customers', function (Blueprint $table) {
            $table->bigInteger('telegram_chat_id')->nullable()->unique()->after('default_lang');
            $table->string('telegram_link_code', 10)->nullable()->after('telegram_chat_id');
            $table->timestamp('telegram_link_code_expires_at')->nullable()->after('telegram_link_code');
        });
    }

    public function down()
    {
        Schema::table('transit_customers', function (Blueprint $table) {
            $table->dropColumn(['telegram_chat_id', 'telegram_link_code', 'telegram_link_code_expires_at']);
        });
    }
}
