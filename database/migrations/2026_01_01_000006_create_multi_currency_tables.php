<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add multi-currency attributes to existing currencies table if not present
        Schema::table('currencies', function (Blueprint $table) {
            if (!Schema::hasColumn('currencies', 'country_code')) {
                $table->string('country_code', 2)->nullable();
                $table->string('flag', 10)->nullable();
                $table->integer('decimal_precision')->default(2);
                $table->decimal('min_deposit', 18, 4)->default(10.0000);
                $table->decimal('max_deposit', 18, 4)->default(100000.0000);
                $table->decimal('min_withdrawal', 18, 4)->default(10.0000);
                $table->decimal('max_withdrawal', 18, 4)->default(50000.0000);
                $table->decimal('deposit_fee_flat', 18, 4)->default(0.0000);
                $table->decimal('deposit_fee_percent', 5, 2)->default(0.00);
                $table->decimal('withdrawal_fee_flat', 18, 4)->default(0.0000);
                $table->decimal('withdrawal_fee_percent', 5, 2)->default(0.00);
                $table->integer('display_position')->default(0);
                $table->timestamp('last_rate_update')->nullable();
            }
        });

        // Add currency_code to users table for quick lookup
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'currency_code')) {
                $table->string('currency_code', 10)->default('USD');
            }
        });

        // Exchange rates history
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('base_currency', 10)->default('USD');
            $table->string('target_currency', 10);
            $table->decimal('rate', 18, 6);
            $table->string('source')->default('manual'); // manual, api
            $table->timestamps();
        });

        // Currency change requests
        Schema::create('currency_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('current_currency', 10);
            $table->string('requested_currency', 10);
            $table->text('reason');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
            $table->timestamps();
        });

        // Currency change logs
        Schema::create('currency_change_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('old_currency', 10);
            $table->string('new_currency', 10);
            $table->unsignedBigInteger('changed_by_admin_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currency_change_logs');
        Schema::dropIfExists('currency_change_requests');
        Schema::dropIfExists('exchange_rates');
    }
};
