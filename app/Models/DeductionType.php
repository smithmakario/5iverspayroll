<?php

namespace App\Models;

use App\Enums\CalculationType;
use App\Enums\DeductionCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeductionType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category',
        'calculation_type',
        'default_amount',
        'default_rate',
        'is_active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'category' => DeductionCategory::class,
            'calculation_type' => CalculationType::class,
            'default_amount' => 'decimal:2',
            'default_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function employeeDeductions(): HasMany
    {
        return $this->hasMany(EmployeeDeduction::class);
    }

    public function payslipItems(): HasMany
    {
        return $this->hasMany(PayslipItem::class);
    }
}
