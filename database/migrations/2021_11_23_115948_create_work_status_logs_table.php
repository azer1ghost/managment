<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkStatusLogsTable extends Migration
{
    public function up()
    {
        Schema::create('work_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->integer('status')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_status_logs');
    }
}
