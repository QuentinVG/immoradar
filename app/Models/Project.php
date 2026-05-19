<?php

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read User|null $user
 * @property-read Collection<int, Property> $properties
 */
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'max_budget',
        'target_monthly_cost',
        'reference_location',
        'max_commute_minutes',
        'min_surface',
        'requires_garage',
        'max_work_cost',
        'min_dpe',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'max_budget' => 'decimal:2',
            'target_monthly_cost' => 'decimal:2',
            'max_commute_minutes' => 'integer',
            'min_surface' => 'decimal:2',
            'requires_garage' => 'boolean',
            'max_work_cost' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Property, $this>
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
