<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfficialSalaryToSalariesTable extends Migration
{
    public function up(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->decimal('official_salary', 10, 2)->nullable()->after('company_id')
                ->comment('Həmin şirkətdəki rəsmi əmək haqqı');
        });
    }

    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropColumn('official_salary');
        });
    }
}
