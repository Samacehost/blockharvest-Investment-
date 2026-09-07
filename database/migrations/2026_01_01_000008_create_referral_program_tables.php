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
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 32)->nullable()->unique()->after('uuid');
            $table->foreignId('referred_by_id')->nullable()->after('referral_code')->constrained('users')->nullOnDelete();
        });

        Schema::table('wallets', function (Blueprint $table) {
            $table->decimal('referral_balance', 16, 4)->default(0.0000)->after('invested_balance');
        });

        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('investment_id')->nullable()->constrained('investments')->nullOnDelete();
            $table->decimal('investment_amount', 16, 4);
            $table->decimal('commission_rate', 5, 2)->default(5.00); // 5.00%
            $table->decimal('commission_amount', 16, 4);
            $table->string('currency_code', 8)->default('USD');
            $table->string('status', 32)->default('credited');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');

        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('referral_balance');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by_id']);
            $table->dropColumn(['referral_code', 'referred_by_id']);
        });
    }
};
