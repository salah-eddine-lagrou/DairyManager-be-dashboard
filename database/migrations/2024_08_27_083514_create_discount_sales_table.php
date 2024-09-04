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
        Schema::create('discount_sales', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount');
            $table->enum('discount_type', ['permanent-discounts', 'periodic-discounts']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_sales');
    }
};
