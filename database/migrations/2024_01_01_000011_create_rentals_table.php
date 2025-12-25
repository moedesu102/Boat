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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('restrict');
            $table->foreignId('boat_id')->constrained('boats')->onDelete('restrict');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('restrict');
            $table->integer('passenger_count');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])->default('pending');
            $table->string('duration_text', 50)->nullable();
            $table->decimal('duration_hours', 4, 1)->nullable();
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'refunded', 'cancelled'])->default('pending');
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_type', 50)->nullable();
            $table->string('payment_transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->text('special_requests')->nullable();
            $table->integer('contract_template_id')->nullable();
            $table->timestamp('contract_signed_at')->nullable();
            $table->text('contract_signature_data')->nullable();
            $table->json('addons_json')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('source', 50)->nullable();
            $table->string('color', 20)->nullable();
            $table->string('signature_token')->nullable();
            $table->boolean('temp_license_required')->default(false);
            $table->timestamp('temp_license_signed_at')->nullable();
            $table->text('temp_license_signature_data')->nullable();
            $table->integer('temp_license_contract_id')->nullable();
            $table->enum('has_boating_license', ['yes', 'no'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};

