<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pages & Dynamic CMS Sections
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('section_key'); // e.g. hero, how_it_works, features, trust_security, statistics, cta
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('content_json')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Menus & Menu Items
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->unique(); // e.g. header, footer, user_dashboard
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->string('target')->default('_self');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // FAQs, Testimonials, Announcements, Cookie Consents
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('category')->default('General');
            $table->text('question');
            $table->longText('answer');
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('author_role')->nullable();
            $table->string('author_avatar')->nullable();
            $table->text('content');
            $table->integer('rating')->default(5);
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('banner_type')->default('info'); // info, warning, danger, success
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cookie_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address', 45);
            $table->json('consent_data_json')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cookie_consents');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('pages');
    }
};
