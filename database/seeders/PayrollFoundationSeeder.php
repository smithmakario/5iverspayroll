<?php

namespace Database\Seeders;

use App\Enums\CalculationType;
use App\Enums\DeductionCategory;
use App\Enums\EarningCategory;
use App\Models\DeductionType;
use App\Models\EarningType;
use App\Models\Department;
use App\Models\Location;
use App\Models\PayGrade;
use Illuminate\Database\Seeder;

class PayrollFoundationSeeder extends Seeder
{
    public function run(): void
    {
        Department::firstOrCreate(
            ['code' => 'HR'],
            ['name' => 'Human Resources', 'description' => 'People operations and payroll']
        );

        Department::firstOrCreate(
            ['code' => 'FIN'],
            ['name' => 'Finance', 'description' => 'Accounting and finance']
        );

        Location::firstOrCreate(
            ['code' => 'HQ'],
            ['name' => 'Head Office', 'description' => 'Main headquarters']
        );

        Location::firstOrCreate(
            ['code' => 'LAG'],
            ['name' => 'Lagos Branch', 'description' => 'Lagos office']
        );

        PayGrade::firstOrCreate(
            ['code' => 'PG1'],
            ['name' => 'Grade 1', 'base_salary' => 150000, 'currency' => 'NGN']
        );

        PayGrade::firstOrCreate(
            ['code' => 'PG2'],
            ['name' => 'Grade 2', 'base_salary' => 250000, 'currency' => 'NGN']
        );

        PayGrade::firstOrCreate(
            ['code' => 'PG3'],
            ['name' => 'Grade 3', 'base_salary' => 400000, 'currency' => 'NGN']
        );

        DeductionType::firstOrCreate(
            ['code' => 'PAYE'],
            [
                'name' => 'PAYE Tax',
                'category' => DeductionCategory::Tax,
                'calculation_type' => CalculationType::Percentage,
                'default_rate' => 7.5,
            ]
        );

        DeductionType::firstOrCreate(
            ['code' => 'PENSION'],
            [
                'name' => 'Pension Contribution',
                'category' => DeductionCategory::Statutory,
                'calculation_type' => CalculationType::Percentage,
                'default_rate' => 8,
            ]
        );

        DeductionType::firstOrCreate(
            ['code' => 'NHF'],
            [
                'name' => 'National Housing Fund',
                'category' => DeductionCategory::Statutory,
                'calculation_type' => CalculationType::Percentage,
                'default_rate' => 2.5,
            ]
        );

        EarningType::firstOrCreate(
            ['code' => 'PERF_BONUS'],
            [
                'name' => 'Performance Bonus',
                'category' => EarningCategory::Bonus,
                'calculation_type' => CalculationType::Fixed,
                'default_amount' => 50000,
                'description' => 'Periodic performance-based bonus',
            ]
        );

        EarningType::firstOrCreate(
            ['code' => 'SALES_COMM'],
            [
                'name' => 'Sales Commission',
                'category' => EarningCategory::Commission,
                'calculation_type' => CalculationType::Percentage,
                'default_rate' => 5,
                'description' => 'Percentage commission on base pay',
            ]
        );

        EarningType::firstOrCreate(
            ['code' => 'TRANSPORT'],
            [
                'name' => 'Transport Allowance',
                'category' => EarningCategory::Allowance,
                'calculation_type' => CalculationType::Fixed,
                'default_amount' => 15000,
            ]
        );
    }
}
