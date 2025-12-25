<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds critical indexes for performance optimization
     */
    public function up(): void
    {
        // ============================================
        // RENTALS TABLE - Priority 1 (CRITICAL)
        // ============================================
        Schema::table('rentals', function (Blueprint $table) {
            // Single column indexes for filtering
            $table->index('start_time', 'idx_rentals_start_time');
            $table->index('end_time', 'idx_rentals_end_time');
            $table->index('status', 'idx_rentals_status');
            $table->index('payment_status', 'idx_rentals_payment_status');
            
            // Composite indexes for common query patterns
            // Check boat availability: WHERE boat_id = ? AND status IN (...) AND (start_time, end_time) overlap
            $table->index(['boat_id', 'status', 'start_time', 'end_time'], 'idx_rentals_boat_availability');
            
            // Branch dashboard: WHERE branch_id = ? AND status = ? ORDER BY start_time
            $table->index(['branch_id', 'status', 'start_time'], 'idx_rentals_branch_status_time');
            
            // Customer history: WHERE customer_id = ? AND status = ?
            $table->index(['customer_id', 'status'], 'idx_rentals_customer_status');
            
            // Reports: WHERE status = ? AND payment_status = ? ORDER BY start_time
            $table->index(['status', 'payment_status', 'start_time'], 'idx_rentals_status_payment_time');
            
            // Date range queries: WHERE start_time >= ? AND end_time <= ?
            $table->index(['start_time', 'end_time'], 'idx_rentals_date_range');
        });

        // Note: Foreign key constraint for invoice_id is handled in separate migration
        // See: 2024_01_01_000021_fix_rentals_invoice_id_foreign_key.php

        // ============================================
        // BOATS TABLE - Priority 1 (CRITICAL)
        // ============================================
        Schema::table('boats', function (Blueprint $table) {
            // Single column indexes
            $table->index('status', 'idx_boats_status');
            $table->index('online_bookable', 'idx_boats_online_bookable');
            $table->index('available_online', 'idx_boats_available_online');
            
            // Composite indexes for common queries
            // Available boats for booking: WHERE branch_id = ? AND status = 'available' AND online_bookable = 1
            $table->index(['branch_id', 'status', 'online_bookable'], 'idx_boats_branch_status_bookable');
            
            // Filter by type: WHERE type_id = ? AND status = ? AND available_online = 1
            $table->index(['type_id', 'status', 'available_online'], 'idx_boats_type_status_online');
            
            // Branch boats: WHERE branch_id = ? AND status = ?
            $table->index(['branch_id', 'status'], 'idx_boats_branch_status');
        });

        // ============================================
        // INVOICES TABLE - Priority 2 (HIGH)
        // ============================================
        Schema::table('invoices', function (Blueprint $table) {
            // Single column indexes
            $table->index('status', 'idx_invoices_status');
            $table->index('due_date', 'idx_invoices_due_date');
            
            // Composite indexes
            // Customer invoices: WHERE customer_id = ? AND status = ?
            $table->index(['customer_id', 'status'], 'idx_invoices_customer_status');
            
            // Overdue reports: WHERE branch_id = ? AND status = 'unpaid' AND due_date < NOW()
            $table->index(['branch_id', 'status', 'due_date'], 'idx_invoices_branch_status_due');
            
            // Payment tracking: WHERE status = ? AND due_date < ?
            $table->index(['status', 'due_date'], 'idx_invoices_status_due');
        });

        // ============================================
        // CUSTOMERS TABLE - Priority 2 (HIGH)
        // ============================================
        Schema::table('customers', function (Blueprint $table) {
            // Single column indexes
            $table->index('status', 'idx_customers_status');
            $table->index('is_active', 'idx_customers_is_active');
            
            // Composite index for active customers
            $table->index(['status', 'is_active'], 'idx_customers_status_active');
            
            // Last rental date for reports
            $table->index('last_rental_date', 'idx_customers_last_rental');
        });

        // ============================================
        // PRICING_TIERS TABLE - Priority 2 (HIGH)
        // ============================================
        Schema::table('pricing_tiers', function (Blueprint $table) {
            // Single column indexes
            $table->index('active', 'idx_pricing_tiers_active');
            $table->index('is_default', 'idx_pricing_tiers_is_default');
            
            // Composite index for pricing queries
            // WHERE branch_id = ? AND active = 1 ORDER BY sort_order
            $table->index(['branch_id', 'active', 'sort_order'], 'idx_pricing_tiers_branch_active_sort');
        });

        // ============================================
        // BOAT_IMAGES TABLE - Priority 3 (MEDIUM)
        // ============================================
        Schema::table('boat_images', function (Blueprint $table) {
            // Get primary image: WHERE boat_id = ? AND is_primary = 1
            $table->index(['boat_id', 'is_primary'], 'idx_boat_images_boat_primary');
        });

        // ============================================
        // BOAT_PRICING_TIERS TABLE - Priority 3 (MEDIUM)
        // ============================================
        Schema::table('boat_pricing_tiers', function (Blueprint $table) {
            // Get pricing for boat: WHERE boat_id = ? ORDER BY pricing_tier_id
            $table->index(['boat_id', 'pricing_tier_id'], 'idx_boat_pricing_tiers_boat_tier');
        });

        // ============================================
        // RENTAL_ADDONS TABLE - Priority 3 (MEDIUM)
        // ============================================
        Schema::table('rental_addons', function (Blueprint $table) {
            // Get addons for rental: WHERE rental_id = ?
            // Note: rental_id already has index from foreign key, but adding composite for reports
            $table->index(['rental_id', 'addon_id'], 'idx_rental_addons_rental_addon');
        });

        // ============================================
        // ADDONS TABLE - Priority 3 (MEDIUM)
        // ============================================
        Schema::table('addons', function (Blueprint $table) {
            // Filter active addons by branch
            $table->index(['branch_id', 'status'], 'idx_addons_branch_status');
        });

        // ============================================
        // SIGNATURES TABLE - Priority 3 (MEDIUM)
        // ============================================
        Schema::table('signatures', function (Blueprint $table) {
            // Get signatures by rental
            $table->index(['rental_id', 'signature_type'], 'idx_signatures_rental_type');
            // Get signatures by invoice
            $table->index(['invoice_id', 'signature_type'], 'idx_signatures_invoice_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes in reverse order
        Schema::table('signatures', function (Blueprint $table) {
            $table->dropIndex('idx_signatures_invoice_type');
            $table->dropIndex('idx_signatures_rental_type');
        });

        Schema::table('addons', function (Blueprint $table) {
            $table->dropIndex('idx_addons_branch_status');
        });

        Schema::table('rental_addons', function (Blueprint $table) {
            $table->dropIndex('idx_rental_addons_rental_addon');
        });

        Schema::table('boat_pricing_tiers', function (Blueprint $table) {
            $table->dropIndex('idx_boat_pricing_tiers_boat_tier');
        });

        Schema::table('boat_images', function (Blueprint $table) {
            $table->dropIndex('idx_boat_images_boat_primary');
        });

        Schema::table('pricing_tiers', function (Blueprint $table) {
            $table->dropIndex('idx_pricing_tiers_branch_active_sort');
            $table->dropIndex('idx_pricing_tiers_is_default');
            $table->dropIndex('idx_pricing_tiers_active');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('idx_customers_last_rental');
            $table->dropIndex('idx_customers_status_active');
            $table->dropIndex('idx_customers_is_active');
            $table->dropIndex('idx_customers_status');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('idx_invoices_status_due');
            $table->dropIndex('idx_invoices_branch_status_due');
            $table->dropIndex('idx_invoices_customer_status');
            $table->dropIndex('idx_invoices_due_date');
            $table->dropIndex('idx_invoices_status');
        });

        Schema::table('boats', function (Blueprint $table) {
            $table->dropIndex('idx_boats_branch_status');
            $table->dropIndex('idx_boats_type_status_online');
            $table->dropIndex('idx_boats_branch_status_bookable');
            $table->dropIndex('idx_boats_available_online');
            $table->dropIndex('idx_boats_online_bookable');
            $table->dropIndex('idx_boats_status');
        });

        Schema::table('rentals', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['invoice_id']);
            
            // Drop indexes
            $table->dropIndex('idx_rentals_date_range');
            $table->dropIndex('idx_rentals_status_payment_time');
            $table->dropIndex('idx_rentals_customer_status');
            $table->dropIndex('idx_rentals_branch_status_time');
            $table->dropIndex('idx_rentals_boat_availability');
            $table->dropIndex('idx_rentals_payment_status');
            $table->dropIndex('idx_rentals_status');
            $table->dropIndex('idx_rentals_end_time');
            $table->dropIndex('idx_rentals_start_time');
        });
    }
};

