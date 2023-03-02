<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersCommandsRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_commands_relationship', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('command_id')->nullable()->index()->constrained()->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_commands_relationship');
    }
}
