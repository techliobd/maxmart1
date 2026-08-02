<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add admin-specific fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('email_verified_at');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
        });

        // Permission tables are created by spatie/laravel-permission package
        // We just need to ensure the migration runs after user creation

        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->json('setting_value')->nullable();
            $table->string('section')->default('general');
            $table->timestamps();
        });

        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('gateway')->unique(); // stripe, paypal, cod, bank_transfer, sslcommerz, bkash, nagad
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->json('credentials')->nullable(); // Encrypted credentials
            $table->json('settings')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->boolean('is_test_mode')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // order_confirmation, password_reset, etc.
            $table->string('name');
            $table->string('subject');
            $table->text('body'); // HTML content with placeholders
            $table->text('plain_text_body')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('body');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->string('backup_name');
            $table->string('backup_path');
            $table->decimal('size_mb', 10, 2)->default(0);
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->text('error_message')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Search index table for Scout database driver
        Schema::create('scout_index', function (Blueprint $table) {
            $table->id();
            $table->string('indexable_type');
            $table->unsignedBigInteger('indexable_id');
            $table->json('data');
            $table->timestamps();

            $table->unique(['indexable_type', 'indexable_id'], 'scout_index_unique');
            $table->fullText('data');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scout_index');
        Schema::dropIfExists('backup_logs');
        Schema::dropIfExists('sms_templates');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('payment_gateways');
        Schema::dropIfExists('admin_settings');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'is_active', 'last_login_at', 'last_login_ip']);
        });
    }
};
