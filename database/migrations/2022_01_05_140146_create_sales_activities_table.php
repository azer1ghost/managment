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
            $table->string('name')->nullable();
            $table->timestamp('datetime')->nullable();
            $table->string('address')->nullable();
            $table->string('activity_area')->nullable();
            $table->bigInteger('client_id');
            $table->text('result')->nullable();
            $table->foreignId('organization_id')->nullable()->index()->constrained();
            $table->foreignId('certificate_id')->nullable()->index()->constrained();
            $table->foreignId('sales_activity_type_id')->nullable()->index()->constrained();
            $table->foreignId('user_id')->nullable()->index()->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_activities');
    }
}
