<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Support Categories, Tickets, and Messages
        Schema::create('support_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('support_category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('subject');
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('status')->default('open'); // open, pending, answered, closed
            $table->timestamps();
        });

        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id')->constrained()->onDelete('cascade');
            $table->string('sender_type'); // user, admin
            $table->unsignedBigInteger('sender_id');
            $table->longText('message');
            $table->string('attachment_path')->nullable();
            $table->timestamps();
        });

        // Notifications & Email Templates
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info'); // info, success, warning, danger
            $table->boolean('is_read')->default(false);
            $table->string('action_url')->nullable();
            $table->timestamps();
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. welcome, deposit_approved, roi_credited, kyc_updated
            $table->string('name');
            $table->string('subject');
            $table->longText('body_html');
            $table->json('placeholders_json')->nullable();
            $table->timestamps();
        });

        // Audit Logs, Admin Approvals, System Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event');
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->json('old_values_json')->nullable();
            $table->json('new_values_json')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->default('success'); // success, failed
            $table->timestamps();
        });

        Schema::create('admin_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('action_type');
            $table->string('requestable_type');
            $table->unsignedBigInteger('requestable_id');
            $table->foreignId('requested_by_admin_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('approved_by_admin_id')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->json('payload_json')->nullable();
            $table->timestamps();
        });

        Schema::create('scheduled_job_logs', function (Blueprint $table) {
            $table->id();
            $table->string('job_name');
            $table->string('status'); // success, failed
            $table->text('summary')->nullable();
            $table->timestamp('executed_at');
            $table->timestamps();
        });

        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('gateway');
            $table->string('event_id')->nullable();
            $table->json('payload_json');
            $table->string('status')->default('unprocessed');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
        Schema::dropIfExists('scheduled_job_logs');
        Schema::dropIfExists('admin_approvals');
        Schema::dropIfExists('login_histories');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('support_categories');
    }
};
