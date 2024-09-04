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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('modified_by_id')->nullable();
            $table->foreign('modified_by_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('product_subcategory_id')->nullable();
            $table->foreign('product_subcategory_id')->references('id')->on('product_subcategories')->onDelete('set null');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
            $table->double('wieght');
            $table->double('measure');
            $table->double('price_ht');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->foreign('tax_id')->references('id')->on('tva')->onDelete('set null');
            $table->double('price_ttc');
            $table->enum('status', ['actif', 'inactif']);
            $table->unsignedBigInteger('product_stock_status_id')->nullable();
            $table->foreign('product_stock_status_id')->references('id')->on('product_stock_status')->onDelete('set null');
            $table->unsignedBigInteger('batch_product_id')->nullable();
            $table->foreign('batch_product_id')->references('id')->on('batch_products')->onDelete('set null');
            $table->text('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
