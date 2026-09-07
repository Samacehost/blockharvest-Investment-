<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Investment Plans & Active Investments
        Schema::create('investment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('icon')->nullable();
            $table->decimal('min_amount', 18, 4);
            $table->decimal('max_amount', 18, 4);
            $table->string('currency_code', 10)->default('USD');
            $table->integer('duration_value'); // e.g. 30
            $table->string('duration_unit')->default('days'); // hours, days, weeks, months, years
            $table->decimal('roi_rate', 8, 4); // Percentage rate e.g. 1.5000 %
            $table->string('roi_frequency')->default('daily'); // hourly, daily, weekly, monthly, yearly, at_maturity
            $table->string('calculation_type')->default('simple'); // simple, compound
            $table->boolean('capital_return')->default(true); // Return capital at maturity
            $table->boolean('early_termination_allowed')->default(false);
            $table->decimal('early_termination_fee_percent', 5, 2)->default(0.00);
            $table->boolean('featured')->default(false);
            $table->string('popular_badge')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->longText('terms')->nullable();
            $table->timestamps();
        });

        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 30)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('investment_plan_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 18, 4);
            $table->string('currency_code', 10)->default('USD');
            $table->decimal('roi_rate', 8, 4);
            $table->string('roi_frequency');
            $table->string('calculation_type');
            $table->boolean('capital_return');
            $table->decimal('total_projected_roi', 18, 4);
            $table->decimal('total_earned_roi', 18, 4)->default(0.0000);
            $table->string('status')->default('active'); // pending, active, matured, completed, cancelled, terminated
            $table->timestamp('started_at')->nullable();
            $table->timestamp('next_payout_at')->nullable();
            $table->timestamp('matures_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('investment_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 18, 4);
            $table->text('calculation_snapshot')->nullable();
            $table->timestamp('earned_at');
            $table->unsignedBigInteger('ledger_entry_id')->nullable();
            $table->timestamps();
        });

        // Wallets & Ledger System
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('currency_code', 10)->default('USD');
            $table->decimal('available_balance', 18, 4)->default(0.0000);
            $table->decimal('invested_balance', 18, 4)->default(0.0000);
            $table->decimal('earnings_balance', 18, 4)->default(0.0000);
            $table->decimal('pending_deposit_balance', 18, 4)->default(0.0000);
            $table->decimal('pending_withdrawal_balance', 18, 4)->default(0.0000);
            $table->timestamps();

            $table->unique(['user_id', 'currency_code']);
        });

        Schema::create('ledger_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name');
            $table->string('type'); // asset, liability, equity, revenue, expense
            $table->timestamps();
        });

        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 40)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_type'); // DEPOSIT_CREDIT, INVESTMENT_DEBIT, ROI_EARNING_CREDIT, CAPITAL_RETURN_CREDIT, WITHDRAWAL_HOLD, WITHDRAWAL_PAYOUT, WITHDRAWAL_REJECT_RELEASE, ADMIN_ADJUSTMENT
            $table->string('direction', 10); // DEBIT, CREDIT
            $table->decimal('amount', 18, 4);
            $table->string('currency_code', 10)->default('USD');
            $table->decimal('balance_before', 18, 4);
            $table->decimal('balance_after', 18, 4);
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->unsignedBigInteger('processed_by_admin_id')->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('user_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('ledger_accounts');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('investment_earnings');
        Schema::dropIfExists('investments');
        Schema::dropIfExists('investment_plans');
    }
};
