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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->string('email_verification_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password_reset_token')->nullable();
            $table->timestamp('password_reset_expires')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('phone', 50)->nullable();
            $table->string('phone_primary', 50)->nullable();
            $table->string('phone_secondary', 50)->nullable();
            $table->string('address')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state_province', 100)->nullable();
            $table->string('postal_code', 50)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship', 100)->nullable();
            $table->string('emergency_contact_phone', 50)->nullable();
            $table->string('emergency_contact_email')->nullable();
            $table->string('license_number', 100)->nullable();
            $table->string('drivers_license_number', 100)->nullable();
            $table->string('drivers_license_state', 100)->nullable();
            $table->date('drivers_license_expiry')->nullable();
            $table->string('boating_license_number', 100)->nullable();
            $table->string('boating_license_state', 100)->nullable();
            $table->date('boating_license_expiry')->nullable();
            $table->string('status_card_number', 100)->nullable();
            $table->date('status_card_expiry')->nullable();
            $table->boolean('tax_exempt')->default(false);
            $table->string('tax_exempt_reason')->nullable();
            $table->string('passport_number', 100)->nullable();
            $table->string('passport_country', 100)->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('medications')->nullable();
            $table->string('swimming_ability', 50)->nullable();
            $table->string('boating_experience', 100)->nullable();
            $table->text('special_needs')->nullable();
            $table->string('preferred_boat_types')->nullable();
            $table->integer('typical_group_size')->nullable();
            $table->string('preferred_rental_duration', 50)->nullable();
            $table->text('special_requests')->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('customer_since')->nullable();
            $table->boolean('communication_email')->default(true);
            $table->boolean('communication_sms')->default(false);
            $table->boolean('communication_phone')->default(true);
            $table->boolean('marketing_emails')->default(false);
            $table->enum('status', ['active', 'inactive', 'blacklisted'])->default('active');
            $table->boolean('vip_status')->default(false);
            $table->boolean('credit_approved')->default(false);
            $table->decimal('credit_limit', 10, 2)->nullable();
            $table->boolean('requires_supervision')->default(false);
            $table->text('blacklist_reason')->nullable();
            $table->timestamp('last_rental_date')->nullable();
            $table->integer('total_rentals')->default(0);
            $table->decimal('total_spent', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

