<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('icon')->nullable();
            $table->text('detail')->nullable();
            $table->foreignId('company_id')->nullable()->index()->constrained()->onDelete('SET NULL');
            $table->foreignId('department_id')->nullable()->index()->constrained()->onDelete('SET NULL');
            $table->foreignId('service_id')->nullable()->index()->constrained()->onDelete('SET NULL');
            $table->boolean('has_asan_imza');
            $table->softDeletes();
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
        Schema::dropIfExists('services');
    }
}
