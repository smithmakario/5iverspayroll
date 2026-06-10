<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\EmployeeGuarantor;
use App\Models\Payslip;
use App\Policies\PayslipPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Payslip::class, PayslipPolicy::class);

        Route::bind('guarantor', function (string $value, $route) {
            $employee = $route->parameter('employee');

            if ($employee instanceof Employee) {
                return $employee->guarantors()->whereKey($value)->firstOrFail();
            }

            return EmployeeGuarantor::query()->whereKey($value)->firstOrFail();
        });
    }
}
