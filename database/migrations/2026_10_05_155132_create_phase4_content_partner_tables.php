<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['individual', 'organization'])->default('individual');
            $table->string('name');
            $table->string('contact_email')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->timestamps();
        });

        Schema::create('course_licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('content_partner_id')->constrained('content_partners')->onDelete('cascade');
            $table->enum('status', ['pending', 'active', 'expired', 'terminated', 'legacy_active'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('exclusivity')->default(false);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });

        Schema::create('course_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('version_identifier');
            $table->text('change_summary')->nullable();
            $table->enum('status', ['draft', 'under_review', 'approved'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });

        Schema::create('partner_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_partner_id')->constrained('content_partners')->onDelete('cascade');
            $table->string('contract_document_path')->nullable();
            $table->enum('status', ['active', 'expired', 'terminated'])->default('active');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('terms_summary')->nullable();
            $table->timestamps();
        });

        Schema::create('partner_payout_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_partner_id')->constrained('content_partners')->onDelete('cascade');
            $table->foreignId('course_license_id')->nullable()->constrained('course_licenses')->onDelete('set null');
            $table->enum('rule_type', ['revenue_share', 'fixed_fee'])->default('revenue_share');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('effective_date')->nullable();
            $table->timestamps();
        });

        Schema::create('partner_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_partner_id')->constrained('content_partners')->onDelete('cascade');
            $table->foreignId('rule_id')->nullable()->constrained('partner_payout_rules')->onDelete('set null');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_payouts');
        Schema::dropIfExists('partner_payout_rules');
        Schema::dropIfExists('partner_contracts');
        Schema::dropIfExists('course_versions');
        Schema::dropIfExists('course_licenses');
        Schema::dropIfExists('content_partners');
    }
};
