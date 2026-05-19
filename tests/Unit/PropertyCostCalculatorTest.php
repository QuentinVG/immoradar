<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Services\PropertyCostCalculator;
use Tests\TestCase;

class PropertyCostCalculatorTest extends TestCase
{
    public function test_it_calculates_monthly_loan_payment(): void
    {
        $calculator = new PropertyCostCalculator;

        $payment = $calculator->monthlyLoanPayment(150000, 3.6, 20);

        $this->assertGreaterThan(870, $payment);
        $this->assertLessThan(890, $payment);
    }

    public function test_it_calculates_real_monthly_cost(): void
    {
        $property = new Property([
            'transaction_type' => 'achat',
            'price' => 150000,
            'estimated_work_cost' => 6000,
            'down_payment' => 20000,
            'loan_rate' => 3.6,
            'loan_duration_years' => 20,
            'monthly_charges' => 100,
            'yearly_property_tax' => 960,
            'estimated_energy_monthly' => 90,
            'estimated_home_insurance_monthly' => 20,
            'estimated_loan_insurance_monthly' => 30,
        ]);

        $result = (new PropertyCostCalculator)->calculate($property);

        $this->assertSame(11250.0, $result['estimated_notary_fees']);
        $this->assertGreaterThan(1100, $result['real_monthly_cost']);
        $this->assertFalse($result['is_partial']);
    }
}
