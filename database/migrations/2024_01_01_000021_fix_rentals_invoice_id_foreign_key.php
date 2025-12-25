<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration converts invoice_id from integer to foreignId
     * and ensures data integrity before adding the foreign key constraint.
     */
    public function up(): void
    {
        // First, ensure all invoice_id values reference valid invoices or are NULL
        // This is a safety check - remove invalid references if any
        DB::statement('
            UPDATE rentals 
            SET invoice_id = NULL 
            WHERE invoice_id IS NOT NULL 
            AND invoice_id NOT IN (SELECT id FROM invoices)
        ');

        // Now modify the column to be unsigned big integer (matching foreignId)
        Schema::table('rentals', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_id')->nullable()->change();
        });

        // Add the foreign key constraint
        Schema::table('rentals', function (Blueprint $table) {
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->integer('invoice_id')->nullable()->change();
        });
    }
};

