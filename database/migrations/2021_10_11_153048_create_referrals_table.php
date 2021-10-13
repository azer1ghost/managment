<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->integer('total')->default(0);
            $table->double('bonus', '8', '2')->default(0);
            $table->double('efficiency', '8', '2')->default(0);
            $table->double('total_earnings', '8', '2')->default(0);
            $table->integer('total_packages')->default(0);
            $table->double('referral_bonus_percentage', 8, 2)->default(0);
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
        Schema::dropIfExists('referrals');
    }
}
