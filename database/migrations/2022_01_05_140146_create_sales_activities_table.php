<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('sales_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('datetime')->nullable();
            $table->string('address')->nullable();
            $table->string('activity_area')->nullable();
            $table->string('client')->nullable();
            $table->text('results')->nullable();
            $table->foreignId('organization_id')->nullable()->index()->constrained();
            $table->foreignId('certification_id')->nullable()->index()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_activities');
    }
}
