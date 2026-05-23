<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->isMethod('post')) {
            return;
        }

        $defaults = [
            'property_type' => 'appartement',
            'transaction_type' => 'achat',
            'dpe' => 'inconnu',
            'status' => 'nouveau',
        ];

        $missingDefaults = [];
        foreach ($defaults as $field => $value) {
            if (! $this->filled($field)) {
                $missingDefaults[$field] = $value;
            }
        }

        if ($missingDefaults !== []) {
            $this->merge($missingDefaults);
        }
    }

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
            'title' => ['required', 'string', 'max:160'],
            'listing_url' => ['nullable', 'url', 'max:500'],
            'city' => ['required', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'property_type' => ['required', 'in:appartement,maison,terrain,autre'],
            'transaction_type' => ['required', 'in:achat,location'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'surface' => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'rooms' => ['nullable', 'integer', 'min:0', 'max:100'],
            'bedrooms' => ['nullable', 'integer', 'min:0', 'max:100'],
            'dpe' => ['required', 'in:A,B,C,D,E,F,G,inconnu'],
            'monthly_charges' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'yearly_property_tax' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'estimated_energy_monthly' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'estimated_home_insurance_monthly' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'estimated_loan_insurance_monthly' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'estimated_work_cost' => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'agency_fees' => ['nullable', 'numeric', 'min:0', 'max:9999999'],
            'bank_fees' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'loan_guarantee_fees' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'down_payment' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'loan_rate' => ['nullable', 'numeric', 'min:0', 'max:30'],
            'loan_duration_years' => ['nullable', 'integer', 'min:1', 'max:40'],
            'has_garage' => ['sometimes', 'boolean'],
            'has_parking' => ['sometimes', 'boolean'],
            'has_balcony' => ['sometimes', 'boolean'],
            'has_garden' => ['sometimes', 'boolean'],
            'has_cellar' => ['sometimes', 'boolean'],
            'has_elevator' => ['sometimes', 'boolean'],
            'floor' => ['nullable', 'integer', 'min:-5', 'max:200'],
            'commute_minutes' => ['nullable', 'integer', 'min:0', 'max:300'],
            'status' => ['required', 'in:nouveau,à_analyser,à_visiter,visité,favori,offre_envisagée,offre_faite,rejeté,archivé'],
            'hot_feeling_score' => ['nullable', 'integer', 'min:1', 'max:10'],
            'cold_feeling_score' => ['nullable', 'integer', 'min:1', 'max:10'],
            'rational_notes' => ['nullable', 'string', 'max:5000'],
            'emotional_notes' => ['nullable', 'string', 'max:5000'],
            'risk_notes' => ['nullable', 'string', 'max:5000'],
            'main_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
