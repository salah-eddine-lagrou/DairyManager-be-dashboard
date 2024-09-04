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
        Schema::create('tourne_vendeur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tourne_id')->nullable();
            $table->foreign('tourne_id')->references('id')->on('tournes')->onDelete('set null');
            $table->unsignedBigInteger('vendeur_id')->nullable();
            $table->foreign('vendeur_id')->references('id')->on('users')->onDelete('set null');
            $table->boolean('owner');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourne_vendeur');
    }
};
