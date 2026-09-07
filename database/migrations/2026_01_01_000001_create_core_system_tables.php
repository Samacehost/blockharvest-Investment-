<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // System & Dynamic Branding Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group_name')->default('general');
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type')->default('string'); // string, text, boolean, integer, decimal, json
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        Schema::create('branding_settings', function (Blueprint $table) {
            $table->id();
            $table->string('primary_color')->default('#4F46E5');
            $table->string('secondary_color')->default('#0EA5E9');
            $table->string('accent_color')->default('#F59E0B');
            $table->string('button_radius')->default('0.5rem');
            $table->string('font_family')->default('Inter');
            $table->string('light_logo')->nullable();
            $table->string('dark_logo')->nullable();
            $table->string('favicon')->nullable();
            $table->timestamps();
        });

        // Countries & Currencies
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name');
            $table->string('phone_code', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_investment_allowed')->default(true);
            $table->timestamps();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('symbol', 10);
            $table->decimal('exchange_rate_to_default', 18, 6)->default(1.000000);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // User Profiles & Addresses & Credentials
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('avatar')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('occupation')->nullable();
            $table->timestamps();
        });

        Schema::create('user_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('routing_swift')->nullable();
            $table->boolean('is_primary')->default(true);
            $table->timestamps();
        });

        Schema::create('user_crypto_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('currency_code', 10);
            $table->string('network')->nullable(); // e.g. ERC20, TRC20, Bitcoin
            $table->string('wallet_address');
            $table->string('label')->nullable();
            $table->boolean('is_primary')->default(true);
            $table->timestamps();
        });

        // Roles & Permissions
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('group_name')->default('general');
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_crypto_wallets');
        Schema::dropIfExists('user_bank_accounts');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('branding_settings');
        Schema::dropIfExists('settings');
    }
};
