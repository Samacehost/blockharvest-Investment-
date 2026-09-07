<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Deposit Methods & Deposits
        Schema::create('deposit_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // e.g. bank_transfer, btc, usdt_trc20, stripe
            $table->string('type')->default('manual'); // manual, gateway
            $table->string('logo')->nullable();
            $table->longText('instructions')->nullable();
            $table->decimal('min_amount', 18, 4)->default(10.0000);
            $table->decimal('max_amount', 18, 4)->default(100000.0000);
            $table->decimal('fee_flat', 18, 4)->default(0.0000);
            $table->decimal('fee_percent', 5, 2)->default(0.00);
            $table->string('currency_code', 10)->default('USD');
            $table->json('bank_details_json')->nullable();
            $table->json('crypto_address_json')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 30)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('deposit_method_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 18, 4);
            $table->decimal('fee', 18, 4)->default(0.0000);
            $table->decimal('net_amount', 18, 4);
            $table->string('currency_code', 10)->default('USD');
            $table->string('payment_proof_path')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('approved_by_admin_id')->nullable();
            $table->timestamps();
        });

        // Withdrawal Methods & Withdrawals
        Schema::create('withdrawal_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('min_amount', 18, 4)->default(10.0000);
            $table->decimal('max_amount', 18, 4)->default(50000.0000);
            $table->decimal('fee_flat', 18, 4)->default(0.0000);
            $table->decimal('fee_percent', 5, 2)->default(0.00);
            $table->string('currency_code', 10)->default('USD');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 30)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('withdrawal_method_id')->constrained()->onDelete('restrict');
            $table->decimal('amount', 18, 4);
            $table->decimal('fee', 18, 4)->default(0.0000);
            $table->decimal('net_amount', 18, 4);
            $table->string('currency_code', 10)->default('USD');
            $table->json('destination_details_json');
            $table->string('status')->default('pending'); // pending, approved, rejected, processed, cancelled
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('approved_by_admin_id')->nullable();
            $table->timestamps();
        });

        // KYC Submissions & Documents
        Schema::create('kyc_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('id_type'); // passport, national_id, drivers_license
            $table->string('id_number')->nullable();
            $table->string('status')->default('pending'); // pending, under_review, approved, rejected, resubmission_required
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('kyc_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kyc_submission_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // id_front, id_back, proof_of_address, selfie
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_documents');
        Schema::dropIfExists('kyc_submissions');
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('withdrawal_methods');
        Schema::dropIfExists('deposits');
        Schema::dropIfExists('deposit_methods');
    }
};
