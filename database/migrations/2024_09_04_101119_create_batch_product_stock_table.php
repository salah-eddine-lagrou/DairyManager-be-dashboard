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
        Schema::create('batch_product_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_stock_id')->nullable();
            $table->foreign('product_stock_id')->references('id')->on('product_stock')->onDelete('set null');
            $table->float('responsable_measure_batches')->nullable();
            $table->float('magasinier_measure_batches')->nullable();
            $table->float('measure_batches')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_product_stock');
    }
};
