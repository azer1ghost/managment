<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{

    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();
            $table->json('detail')->nullable();
            $table->boolean('is_certificate')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('organizations');
    }
}
