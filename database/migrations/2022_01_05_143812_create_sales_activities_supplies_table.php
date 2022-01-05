<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesActivitiesSuppliesTable extends Migration
{
    public function up()
    {
        Schema::create('sales_activities_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_activity_id')->nullable()->index()->constrained();
            $table->string('name')->nullable();
            $table->float('value')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_activities_supplies');
    }
}
