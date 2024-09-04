<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('phone');
            $table->double('plafond_vendeur');
            $table->string('pda_code_access');
            $table->string('printer_code');
            $table->boolean('non_tolerated_sales_block');
            $table->double('credit_limit');
            $table->string('username')->unique();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('modified_by_id')->nullable();
            $table->foreign('modified_by_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles_created')->onDelete('cascade');
            $table->enum('status', ['actif', 'inactif']);
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->foreign('responsable_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('magasinier_id')->nullable();
            $table->foreign('magasinier_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            $table->unsignedBigInteger('sector_id')->nullable();
            $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('cascade');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('tournes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('tourne_vendeur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tourne_id')->nullable();
            $table->foreign('tourne_id')->references('id')->on('tournes')->onDelete('set null');
            $table->unsignedBigInteger('vendeur_id')->nullable();
            $table->foreign('vendeur_id')->references('id')->on('users')->onDelete('set null');
            $table->boolean('owner');
            $table->timestamps();
        });

        Schema::create('roles_created', function (Blueprint $table) {
            $table->id();
            $table->enum('role_name', ['admin', 'vendeur', 'responsable', 'magasinier']);
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('permissions_created', function (Blueprint $table) {
            $table->id();
            $table->string('permission');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('tournes');
        Schema::dropIfExists('tourne_vendeur');
        Schema::dropIfExists('roles_created');
        Schema::dropIfExists('permissions_created');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
