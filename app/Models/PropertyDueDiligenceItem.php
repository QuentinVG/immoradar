<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Property|null $property
 */
class PropertyDueDiligenceItem extends Model
{
    protected $fillable = [
        'property_id',
        'key',
        'label',
        'why',
        'action',
        'status',
        'is_blocking',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'is_blocking' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Property, $this>
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
