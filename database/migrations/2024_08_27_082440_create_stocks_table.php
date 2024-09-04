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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('vendeur_id')->nullable();
            $table->foreign('vendeur_id')->references('id')->on('users')->onDelete('set null');
            $table->enum('movement_type', ['chargement', 'dechargement', 'transfert', 'reception' , 'offert', 'retour']);
            // ! we need to add some status related to the real status of the stock $table->enum('status_stock', ['vendable', 'non-vendable']);
            $table->unsignedBigInteger('vendeur_transfert_id')->nullable();
            $table->foreign('vendeur_transfert_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null');
            $table->enum('stock_status', ['valide', 'en-attente', 'refus']);
            $table->timestamps();
        });

        Schema::create('product_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_id');
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->enum('product_stock_status', ['vendable', 'non-vendable', 'reserve']);
            $table->unsignedBigInteger('batch_product_stock_id')->unique();
            $table->foreign('batch_product_stock_id')->references('id')->on('batch_product_stock')->onDelete('cascade');
            $table->double('measure_items');
            $table->double('total_measures');
            $table->timestamps();
        });

        Schema::create('batch_product_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_stock_id')->unique();
            $table->foreign('product_stock_id')->references('id')->on('product_stock')->onDelete('cascade');
            $table->double('measure_batches');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('product_stock');
        Schema::dropIfExists('batch_product_stock');
    }
};
