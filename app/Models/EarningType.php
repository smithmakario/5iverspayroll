<?php

namespace App\Models;

use App\Enums\CalculationType;
use App\Enums\EarningCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EarningType extends Model
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
            'category' => EarningCategory::class,
            'calculation_type' => CalculationType::class,
            'default_amount' => 'decimal:2',
            'default_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function employeeEarnings(): HasMany
    {
        return $this->hasMany(EmployeeEarning::class);
    }

    public function payslipItems(): HasMany
    {
        return $this->hasMany(PayslipItem::class);
    }
}
