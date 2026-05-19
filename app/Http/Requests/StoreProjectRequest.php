<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', 'in:achat,location,investissement'],
            'max_budget' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'target_monthly_cost' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'reference_location' => ['nullable', 'string', 'max:160'],
            'max_commute_minutes' => ['nullable', 'integer', 'min:0', 'max:300'],
            'min_surface' => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'requires_garage' => ['sometimes', 'boolean'],
            'max_work_cost' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'min_dpe' => ['nullable', 'in:A,B,C,D,E,F,G,inconnu'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
