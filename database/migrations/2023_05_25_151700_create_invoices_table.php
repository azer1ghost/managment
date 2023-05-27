<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('company')->nullable();
            $table->integer('client')->nullable();
            $table->string('invoiceNo')->nullable();
            $table->string('invoiceDate')->nullable();
            $table->string('paymentType')->nullable();
            $table->string('protocolDate')->nullable();
            $table->string('contractNo')->nullable();
            $table->string('contractDate')->nullable();
            $table->longText('services')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
