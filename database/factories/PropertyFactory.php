<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => 'Appartement test',
            'city' => 'Valence',
            'property_type' => 'appartement',
            'transaction_type' => 'achat',
            'price' => 160000,
            'surface' => 65,
            'rooms' => 3,
            'bedrooms' => 2,
            'dpe' => 'C',
            'monthly_charges' => 100,
            'yearly_property_tax' => 900,
            'estimated_energy_monthly' => 90,
            'estimated_home_insurance_monthly' => 20,
            'estimated_loan_insurance_monthly' => 30,
            'estimated_work_cost' => 5000,
            'down_payment' => 20000,
            'loan_rate' => 3.7,
            'loan_duration_years' => 20,
            'has_garage' => true,
            'has_parking' => false,
            'has_balcony' => false,
            'has_garden' => false,
            'has_cellar' => false,
            'has_elevator' => false,
            'commute_minutes' => 20,
            'status' => 'nouveau',
            'hot_feeling_score' => 7,
            'cold_feeling_score' => 7,
        ];
    }
}
