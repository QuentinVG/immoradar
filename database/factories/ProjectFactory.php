<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => 'Recherche résidence principale',
            'type' => 'achat',
            'max_budget' => 180000,
            'target_monthly_cost' => 1000,
            'reference_location' => 'Valence',
            'max_commute_minutes' => 35,
            'min_surface' => 60,
            'requires_garage' => true,
            'max_work_cost' => 15000,
            'min_dpe' => 'D',
            'notes' => null,
        ];
    }
}
