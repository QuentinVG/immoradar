<?php

namespace App\Services;

use App\Models\Property;

class PropertyCostCalculator
{
    public const FINANCIAL_NOTICE = 'Estimation indicative, à confirmer avec une banque, un courtier ou un professionnel.';

    /**
     * @return array<string, mixed>
     */
    public function calculate(Property $property): array
    {
        $missing = [];
        $price = $this->money($property->price);
        $workCost = $this->money($property->estimated_work_cost);
        $agencyFees = $this->money($property->agency_fees);
        $bankFees = $this->money($property->bank_fees);
        $loanGuaranteeFees = $this->money($property->loan_guarantee_fees);
        $downPayment = $this->money($property->down_payment);
        $notaryFees = $this->estimatedNotaryFees($property);

        if ($property->transaction_type === 'achat' && $price <= 0) {
            $missing[] = 'prix';
        }

        $purchaseFees = $agencyFees + $bankFees + $loanGuaranteeFees;
        $loanAmount = max(0, $price + $notaryFees + $workCost + $purchaseFees - $downPayment);
        $loanPayment = 0.0;

        if ($property->transaction_type === 'achat') {
            if ($loanAmount > 0 && $property->loan_rate !== null && $property->loan_duration_years !== null) {
                $loanPayment = $this->monthlyLoanPayment($loanAmount, (float) $property->loan_rate, (int) $property->loan_duration_years);
            } else {
                $missing[] = 'taux ou durée de crédit';
            }
        }

        foreach ([
            'monthly_charges' => 'charges mensuelles',
            'yearly_property_tax' => 'taxe foncière',
            'estimated_energy_monthly' => 'énergie estimée',
            'estimated_home_insurance_monthly' => 'assurance habitation',
        ] as $field => $label) {
            if ($property->{$field} === null) {
                $missing[] = $label;
            }
        }

        $details = [
            'credit' => round($loanPayment, 2),
            'charges' => $this->money($property->monthly_charges),
            'property_tax' => round($this->money($property->yearly_property_tax) / 12, 2),
            'energy' => $this->money($property->estimated_energy_monthly),
            'home_insurance' => $this->money($property->estimated_home_insurance_monthly),
            'loan_insurance' => $this->money($property->estimated_loan_insurance_monthly),
            'works_smoothed' => round($workCost / 60, 2),
        ];

        $realMonthlyCost = round(array_sum($details), 2);
        $target = $property->project?->target_monthly_cost ? (float) $property->project->target_monthly_cost : null;

        return [
            'estimated_notary_fees' => round($notaryFees, 2),
            'loan_amount' => round($loanAmount, 2),
            'purchase_fees' => round($purchaseFees, 2),
            'total_project_cost' => round($price + $notaryFees + $workCost + $purchaseFees, 2),
            'estimated_monthly_loan_payment' => round($loanPayment, 2),
            'real_monthly_cost' => $realMonthlyCost,
            'details' => $details,
            'missing' => array_values(array_unique($missing)),
            'is_partial' => count($missing) > 0,
            'budget_badge' => $this->budgetBadge($realMonthlyCost, $target),
            'notice' => self::FINANCIAL_NOTICE,
        ];
    }

    public function estimatedNotaryFees(Property $property): float
    {
        if ($property->transaction_type !== 'achat' || ! $property->price) {
            return 0.0;
        }

        return (float) $property->price * 0.075;
    }

    public function monthlyLoanPayment(float $amount, float $annualRate, int $years): float
    {
        if ($amount <= 0 || $years <= 0) {
            return 0.0;
        }

        $months = $years * 12;
        $monthlyRate = ($annualRate / 100) / 12;

        if ($monthlyRate <= 0) {
            return $amount / $months;
        }

        return $amount * ($monthlyRate / (1 - (1 + $monthlyRate) ** (-$months)));
    }

    private function budgetBadge(float $monthlyCost, ?float $target): string
    {
        if ($target === null || $target <= 0) {
            return 'Budget cible à compléter';
        }

        if ($monthlyCost <= $target) {
            return 'Dans ton budget';
        }

        if ($monthlyCost <= $target * 1.1) {
            return 'Limite';
        }

        return 'Au-dessus du budget';
    }

    private function money(mixed $value): float
    {
        return $value === null ? 0.0 : (float) $value;
    }
}
