<?php

namespace App\Http\Requests;

use App\Services\PropertyDueDiligenceService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDueDiligenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.key' => ['required', 'string', 'max:80'],
            'items.*.status' => ['required', Rule::in(PropertyDueDiligenceService::STATUSES)],
            'items.*.is_blocking' => ['sometimes', 'boolean'],
            'items.*.note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
