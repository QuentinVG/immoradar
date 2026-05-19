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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('listing_url')->nullable();
            $table->string('city');
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->string('property_type');
            $table->string('transaction_type');
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('surface', 8, 2)->nullable();
            $table->unsignedInteger('rooms')->nullable();
            $table->unsignedInteger('bedrooms')->nullable();
            $table->string('dpe')->default('inconnu');
            $table->decimal('monthly_charges', 10, 2)->nullable();
            $table->decimal('yearly_property_tax', 10, 2)->nullable();
            $table->decimal('estimated_energy_monthly', 10, 2)->nullable();
            $table->decimal('estimated_home_insurance_monthly', 10, 2)->nullable();
            $table->decimal('estimated_loan_insurance_monthly', 10, 2)->nullable();
            $table->decimal('estimated_work_cost', 10, 2)->nullable();
            $table->decimal('down_payment', 12, 2)->nullable();
            $table->decimal('loan_rate', 5, 2)->nullable();
            $table->unsignedInteger('loan_duration_years')->nullable();
            $table->boolean('has_garage')->default(false);
            $table->boolean('has_parking')->default(false);
            $table->boolean('has_balcony')->default(false);
            $table->boolean('has_garden')->default(false);
            $table->boolean('has_cellar')->default(false);
            $table->boolean('has_elevator')->default(false);
            $table->integer('floor')->nullable();
            $table->unsignedInteger('commute_minutes')->nullable();
            $table->string('status')->default('nouveau');
            $table->unsignedTinyInteger('hot_feeling_score')->nullable();
            $table->unsignedTinyInteger('cold_feeling_score')->nullable();
            $table->text('rational_notes')->nullable();
            $table->text('emotional_notes')->nullable();
            $table->text('risk_notes')->nullable();
            $table->string('main_photo_path')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
