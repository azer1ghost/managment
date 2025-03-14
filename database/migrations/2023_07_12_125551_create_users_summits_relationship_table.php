<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersSummitsRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_summits_relationship', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('summit_id')->nullable()->index()->constrained()->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_summits_relationship');
    }
}
