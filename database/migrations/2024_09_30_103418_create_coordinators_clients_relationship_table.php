<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoordinatorsClientsRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coordinators_clients_relationship', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('client_id')->nullable()->index()->constrained()->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coordinators_clients_relationship');
    }
}
