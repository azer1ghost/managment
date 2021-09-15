<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentViewerTable extends Migration
{
    public function up()
    {
        Schema::create('comment_viewer', function (Blueprint $table) {
            $table->foreignId('user_id')->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('comment_id')->index()->constrained()->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comment_viewer');
    }
}