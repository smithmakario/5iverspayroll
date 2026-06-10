<?php

namespace App\Http\Controllers;

use App\Enums\PayPeriodFrequency;
use App\Http\Requests\UpdatePayPeriodSettingRequest;
use App\Models\PayPeriodSetting;
use App\Services\PayrollAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PayPeriodSettingController extends Controller
{
    public function edit(): View
    {
        $settings = PayPeriodSetting::current();
        $frequencies = PayPeriodFrequency::cases();

        return view('admin.pay-period-settings', compact('settings', 'frequencies'));
    }

    public function update(UpdatePayPeriodSettingRequest $request): RedirectResponse
    {
        $settings = PayPeriodSetting::current();
        $settings->update($request->validated());

        PayrollAuditLogger::log('pay_period_settings.updated', $settings, $request->validated());

        return back()->with('success', 'Pay period settings updated.');
    }
}
