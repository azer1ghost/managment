<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartmentIdColumnToAsanImzalarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asan_imzalar', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('pin1')->nullable();
            $table->string('pin2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asan_imzalar', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->dropColumn('pin1');
            $table->dropColumn('pin2');
        });
    }
}
