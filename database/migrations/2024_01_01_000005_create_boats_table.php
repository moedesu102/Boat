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
        Schema::create('boats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('boat_types')->onDelete('restrict');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('restrict');
            $table->string('name');
            $table->boolean('is_powered')->default(false);
            $table->integer('capacity');
            $table->decimal('length', 6, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('motor_description')->nullable();
            $table->string('boat_color', 100)->nullable();
            $table->enum('status', ['available', 'unavailable', 'maintenance', 'retired'])->default('available');
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->json('prices_json')->nullable();
            $table->boolean('online_bookable')->default(true);
            $table->boolean('show_on_website')->default(true);
            $table->boolean('available_online')->default(true);
            $table->string('image_url')->nullable();
            $table->string('image_url_2')->nullable();
            $table->string('image_url_3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boats');
    }
};

