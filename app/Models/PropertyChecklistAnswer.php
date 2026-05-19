<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Property|null $property
 * @property-read VisitChecklistQuestion|null $question
 */
class PropertyChecklistAnswer extends Model
{
    protected $fillable = ['property_id', 'visit_checklist_question_id', 'answer', 'score', 'comment'];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Property, $this>
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * @return BelongsTo<VisitChecklistQuestion, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(VisitChecklistQuestion::class, 'visit_checklist_question_id');
    }
}
