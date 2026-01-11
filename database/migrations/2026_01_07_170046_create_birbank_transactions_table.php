<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBirbankTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('birbank_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->enum('env', ['test', 'prod'])->default('test');
            $table->string('account_ref'); // IBAN or accountId
            $table->string('transaction_uid');
            $table->enum('direction', ['in', 'out'])->nullable();
            $table->decimal('amount', 18, 2)->nullable();
            $table->string('currency', 3)->nullable();
            $table->timestamp('booked_at')->nullable();
            $table->text('description')->nullable();
            $table->string('counterparty')->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'env', 'account_ref', 'transaction_uid'], 'birbank_transactions_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('birbank_transactions');
    }
}
