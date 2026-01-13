<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->foreignId('invoice_company_id')
                ->nullable()
                ->after('asan_imza_id')
                ->constrained('companies')
                ->nullOnDelete();
        });

        // Backfill invoice_company_id for existing works based on related AsanImza company
        try {
            DB::table('works')
                ->join('asan_imzalar', 'works.asan_imza_id', '=', 'asan_imzalar.id')
                ->whereNull('works.invoice_company_id')
                ->update([
                    'works.invoice_company_id' => DB::raw('asan_imzalar.company_id'),
                ]);
        } catch (\Throwable $e) {
            // In case of any issue during backfill, just log and continue
            if (function_exists('logger')) {
                logger()->error('Failed to backfill works.invoice_company_id: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            if (Schema::hasColumn('works', 'invoice_company_id')) {
                $table->dropConstrainedForeignId('invoice_company_id');
            }
        });
    }
};

