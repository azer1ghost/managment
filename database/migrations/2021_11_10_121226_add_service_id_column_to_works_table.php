<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceIdColumnToWorksTable extends Migration
{
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->foreignId('service_id')->after('department_id')->nullable()->constrained()->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_id');
        });
    }
}
