<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * OPTIONAL: This migration removes redundant JSON fields
     * Only run this if you've migrated all data from JSON to relational tables
     * 
     * WARNING: Make sure to backup data before running this migration!
     */
    public function up(): void
    {
        // Remove prices_json from boats (data should be in boat_pricing_tiers)
        Schema::table('boats', function (Blueprint $table) {
            $table->dropColumn('prices_json');
        });

        // Remove addons_json from rentals (data should be in rental_addons)
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn('addons_json');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->json('addons_json')->nullable();
        });

        Schema::table('boats', function (Blueprint $table) {
            $table->json('prices_json')->nullable();
        });
    }
};

