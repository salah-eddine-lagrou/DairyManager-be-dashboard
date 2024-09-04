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
        Schema::dropIfExists('batch_product_client_sales');
    }
};
