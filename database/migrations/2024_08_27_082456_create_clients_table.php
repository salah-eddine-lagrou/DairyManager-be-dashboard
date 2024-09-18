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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('qr_client')->unique()->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('ice')->unique()->nullable();
            $table->string('city');
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');
            $table->unsignedBigInteger('client_subcategory_id')->nullable();
            $table->foreign('client_subcategory_id')->references('id')->on('client_subcategories')->onDelete('set null');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('set null');
            $table->unsignedBigInteger('sector_id')->nullable();
            $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('set null');
            $table->string('contact_name');
            $table->string('phone');
            $table->string('address');
            $table->boolean('tour_assignment_commercial')->nullable();
            $table->boolean('client_assignment_commercial')->nullable();
            $table->unsignedBigInteger('price_list_id')->nullable();
            $table->foreign('price_list_id')->references('id')->on('price_lists')->onDelete('set null');
            // ! deleted $table->enum('circuit_distribution', ['direct', 'demi-gros', 'gros']); we could achieve this by the pricelist table
            // TODO payment_terms
            // TODO client_balance
            $table->float('credit_limit');
            $table->float('credit_note_balance');
            $table->float('global_limit');
            $table->string('location');
            $table->string('location_gps_coordinates');
            $table->enum('visit', ['oui', 'non']);
            $table->enum('offert', ['oui', 'non']);
            $table->enum('notification', ['oui', 'non']);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('modified_by_id')->nullable();
            $table->foreign('modified_by_id')->references('id')->on('users')->onDelete('set null');
            $table->enum('status', ['en-attente', 'actif', 'inactif']);
            $table->unsignedBigInteger('tourne_id')->nullable();
            $table->foreign('tourne_id')->references('id')->on('tournes')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
