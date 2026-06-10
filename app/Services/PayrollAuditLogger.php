<?php

namespace App\Services;

use App\Models\PayrollAuditLog;
use Illuminate\Database\Eloquent\Model;

class PayrollAuditLogger
{
    public static function log(string $action, ?Model $subject = null, array $metadata = []): PayrollAuditLog
    {
        return PayrollAuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'metadata' => $metadata ?: null,
        ]);
    }
}
