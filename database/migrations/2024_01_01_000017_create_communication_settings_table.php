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
        Schema::create('communication_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->boolean('sms_booking_enabled')->default(false);
            $table->string('email_method', 50)->nullable();
            $table->string('email_from_address')->nullable();
            $table->string('email_from_name')->nullable();
            $table->string('communication_primary_method', 50)->nullable();
            $table->string('email_booking_customer_subject')->nullable();
            $table->text('email_booking_customer_message')->nullable();
            $table->string('email_booking_store_subject')->nullable();
            $table->text('email_booking_store_message')->nullable();
            $table->boolean('sms_enabled')->default(false);
            $table->string('sms_provider', 50)->nullable();
            $table->string('twilio_account_sid')->nullable();
            $table->string('twilio_auth_token')->nullable();
            $table->string('twilio_from_number', 50)->nullable();
            $table->string('mailgun_api_key')->nullable();
            $table->string('mailgun_domain')->nullable();
            $table->boolean('email_booking_enabled')->default(false);
            $table->string('email_store_address')->nullable();
            $table->boolean('email_notifications_enabled')->default(false);
            $table->boolean('sms_notifications_enabled')->default(false);
            $table->text('message_template_sms')->nullable();
            $table->string('message_template_email_subject')->nullable();
            $table->text('message_template_email_body')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_settings');
    }
};

