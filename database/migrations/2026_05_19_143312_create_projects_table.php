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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
            $table->decimal('max_budget', 12, 2)->nullable();
            $table->decimal('target_monthly_cost', 10, 2)->nullable();
            $table->string('reference_location')->nullable();
            $table->unsignedInteger('max_commute_minutes')->nullable();
            $table->decimal('min_surface', 8, 2)->nullable();
            $table->boolean('requires_garage')->default(false);
            $table->decimal('max_work_cost', 10, 2)->nullable();
            $table->string('min_dpe')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
