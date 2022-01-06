<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdColumnToSalesActivitiesTable extends Migration
{
    public function up()
    {
        Schema::table('sales_activities', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->index()->constrained();
        });
    }

    public function down()
    {
        Schema::table('sales_activities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
}
