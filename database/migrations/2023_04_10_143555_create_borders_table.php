<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('borders', function (Blueprint $table) {
            $table->unsignedBigInteger('country_source');
            $table->unsignedBigInteger('country_border');
            $table->foreign('country_source')->references('id')->on('countries');
            $table->foreign('country_border')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borders');
    }
};
