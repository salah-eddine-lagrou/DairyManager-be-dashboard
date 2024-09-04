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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->double('total_totals');
            $table->double('amount_total');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->unsignedBigInteger('vendeur_id')->nullable();
            $table->foreign('vendeur_id')->references('id')->on('users')->onDelete('set null');
            $table->enum('order_status', ['vente', 'commande', 'offert', 'retour']);
            $table->enum('order_payment_status', ['payee', 'non-payee']);
            $table->timestamps();
        });

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

        Schema::create('batch_product_client_sales', function (Blueprint $table) {
            $table->id();
            $table->double('measure_batches');
            $table->unsignedBigInteger('client_sale_id')->nullable();
            $table->foreign('client_sale_id')->references('id')->on('client_sales')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('client_sales');
        Schema::dropIfExists('batch_product_client_sales');
    }
};
