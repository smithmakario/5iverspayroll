<?php

namespace App\Models;

use App\Enums\PayslipItemType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayslipItem extends Model
{
    protected $fillable = [
        'payslip_id',
        'deduction_type_id',
        'earning_type_id',
        'type',
        'description',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'type' => PayslipItemType::class,
            'amount' => 'decimal:2',
        ];
    }

    public function payslip(): BelongsTo
    {
        return $this->belongsTo(Payslip::class);
    }

    public function deductionType(): BelongsTo
    {
        return $this->belongsTo(DeductionType::class);
    }

    public function earningType(): BelongsTo
    {
        return $this->belongsTo(EarningType::class);
    }
}
