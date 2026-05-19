<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisitChecklistQuestion extends Model
{
    protected $fillable = ['category', 'question', 'help_text', 'weight', 'is_active'];

    protected function casts(): array
    {
        return [
            'weight' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function answers(): HasMany
    {
        return $this->hasMany(PropertyChecklistAnswer::class);
    }
}
