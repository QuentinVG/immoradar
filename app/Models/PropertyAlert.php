<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyAlert extends Model
{
    protected $fillable = ['property_id', 'type', 'severity', 'title', 'message', 'is_resolved'];

    protected function casts(): array
    {
        return [
            'is_resolved' => 'boolean',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
