<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientNameToInquiriesTable extends Migration
{
    public function up()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('client_name')->after('inquiry_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn('client_name');
        });
    }
}
