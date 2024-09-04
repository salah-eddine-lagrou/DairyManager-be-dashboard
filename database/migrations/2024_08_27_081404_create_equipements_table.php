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
        Schema::create('equipements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->integer('quantity');
            $table->unsignedBigInteger('equipement_category_id')->nullable();
            $table->foreign('equipement_category_id')->references('id')->on('equipement_categories')->onDelete('cascade');
            $table->enum('equipement_state', ['confort', 'bon-etat-mais-vide', 'mal-presente', 'autres-produits']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipements');
    }
};
