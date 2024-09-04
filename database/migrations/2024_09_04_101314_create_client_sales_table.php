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
        Schema::create('client_sales', function (Blueprint $table) {
            $table->id();
            $table->double('total');
            $table->double('measure_items');
            $table->double('total_measures');
            $table->date('sale_date');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->unsignedBigInteger('discount_sale_id')->nullable();
            $table->foreign('discount_sale_id')->references('id')->on('discount_sales')->onDelete('set null');
            $table->unsignedBigInteger('price_list_product_details_id')->nullable();
            $table->foreign('price_list_product_details_id')->references('id')->on('price_list_product_details')->onDelete('set null');
            $table->unsignedBigInteger('batch_product_client_sale_id')->nullable();
            $table->foreign('batch_product_client_sale_id')->references('id')->on('batch_product_client_sales')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_sales');
    }
};
