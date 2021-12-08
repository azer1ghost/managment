<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesClientsRelationshipTable extends Migration
{
    public function up()
    {
        Schema::create('sales_clients_relationship', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->index()->constrained()->onDelete('CASCADE');
            $table->foreignId('client_id')->nullable()->index()->constrained()->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_clients_relationship');
    }
}
