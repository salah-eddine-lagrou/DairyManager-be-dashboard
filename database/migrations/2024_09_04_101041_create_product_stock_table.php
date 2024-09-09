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
        Schema::create('product_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_id')->nullable();
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('set null');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->enum('product_stock_status', ['vendable', 'non-vendable', 'reserve']);
            $table->unsignedBigInteger('batch_product_stock_id')->nullable();
            $table->foreign('batch_product_stock_id')->references('id')->on('batch_product_stock')->onDelete('set null');
            $table->enum('approved_status', ['en-attente', 'approuve', 'non-approve'])->default('en-attente');
            $table->double('responsable_measure');
            $table->double('magasinier_measure');
            $table->double('measure_items');
            $table->double('total_measures');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock');
    }
};
