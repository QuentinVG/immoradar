<?php

namespace App\Models;

use Database\Factories\PropertyFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Project|null $project
 * @property-read Collection<int, PropertyAlert> $alerts
 * @property-read Collection<int, PropertyChecklistAnswer> $checklistAnswers
 * @property-read float|null $price_per_square_meter
 */
class Property extends Model
{
    /** @use HasFactory<PropertyFactory> */
    use HasFactory;

    public const DPE_ORDER = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'inconnu' => 8];

    protected $fillable = [
        'project_id',
        'title',
        'listing_url',
        'city',
        'address',
        'description',
        'property_type',
        'transaction_type',
        'price',
        'surface',
        'rooms',
        'bedrooms',
        'dpe',
        'monthly_charges',
        'yearly_property_tax',
        'estimated_energy_monthly',
        'estimated_home_insurance_monthly',
        'estimated_loan_insurance_monthly',
        'estimated_work_cost',
        'down_payment',
        'loan_rate',
        'loan_duration_years',
        'has_garage',
        'has_parking',
        'has_balcony',
        'has_garden',
        'has_cellar',
        'has_elevator',
        'floor',
        'commute_minutes',
        'status',
        'hot_feeling_score',
        'cold_feeling_score',
        'rational_notes',
        'emotional_notes',
        'risk_notes',
        'main_photo_path',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'surface' => 'decimal:2',
            'rooms' => 'integer',
            'bedrooms' => 'integer',
            'monthly_charges' => 'decimal:2',
            'yearly_property_tax' => 'decimal:2',
            'estimated_energy_monthly' => 'decimal:2',
            'estimated_home_insurance_monthly' => 'decimal:2',
            'estimated_loan_insurance_monthly' => 'decimal:2',
            'estimated_work_cost' => 'decimal:2',
            'down_payment' => 'decimal:2',
            'loan_rate' => 'decimal:2',
            'loan_duration_years' => 'integer',
            'has_garage' => 'boolean',
            'has_parking' => 'boolean',
            'has_balcony' => 'boolean',
            'has_garden' => 'boolean',
            'has_cellar' => 'boolean',
            'has_elevator' => 'boolean',
            'floor' => 'integer',
            'commute_minutes' => 'integer',
            'hot_feeling_score' => 'integer',
            'cold_feeling_score' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return HasMany<PropertyChecklistAnswer, $this>
     */
    public function checklistAnswers(): HasMany
    {
        return $this->hasMany(PropertyChecklistAnswer::class);
    }

    /**
     * @return HasMany<PropertyAlert, $this>
     */
    public function alerts(): HasMany
    {
        return $this->hasMany(PropertyAlert::class);
    }

    public function getPricePerSquareMeterAttribute(): ?float
    {
        if (! $this->price || ! $this->surface || (float) $this->surface <= 0) {
            return null;
        }

        return round((float) $this->price / (float) $this->surface);
    }
}
