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
        Schema::create('signature_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('cascade');
            $table->boolean('enabled')->default(false);
            $table->boolean('include_company_logo')->default(true);
            $table->boolean('require_drivers_license')->default(true);
            $table->boolean('include_credit_card_auth')->default(true);
            $table->boolean('require_witness_signature')->default(false);
            $table->text('liability_waiver_text')->nullable();
            $table->text('contract_footer_text')->nullable();
            $table->text('powered_watercraft_requirements')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signature_settings');
    }
};

