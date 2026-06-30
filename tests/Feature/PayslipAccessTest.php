<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayslipAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_employee_can_view_own_payslip_via_user_id_on_employee_record(): void
    {
        [$user, $payslip] = $this->createEmployeeWithPayslip();

        $response = $this->actingAs($user)->get(route('payslips.show', $payslip));

        $response->assertOk();
    }

    public function test_employee_can_view_own_payslip_when_linked_by_email_only(): void
    {
        $user = User::factory()->create(['email' => 'employee@example.com']);
        $user->assignRole(UserRole::Employee->value);

        $employee = Employee::create([
            'employee_number' => 'EMP-001',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'employee@example.com',
            'hire_date' => now()->subYear(),
        ]);

        $payslip = $this->createPayslipForEmployee($employee);

        $response = $this->actingAs($user)->get(route('payslips.show', $payslip));

        $response->assertOk();
    }

    public function test_employee_cannot_view_another_employees_payslip(): void
    {
        [, $payslip] = $this->createEmployeeWithPayslip();

        $otherUser = User::factory()->create();
        $otherUser->assignRole(UserRole::Employee->value);

        Employee::create([
            'user_id' => $otherUser->id,
            'employee_number' => 'EMP-002',
            'first_name' => 'Other',
            'last_name' => 'Person',
            'email' => 'other@example.com',
            'hire_date' => now()->subYear(),
            'profile_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($otherUser)->get(route('payslips.show', $payslip));

        $response->assertForbidden();
    }

    /**
     * @return array{0: User, 1: Payslip}
     */
    private function createEmployeeWithPayslip(): array
    {
        $user = User::factory()->create();
        $user->assignRole(UserRole::Employee->value);

        $employee = Employee::create([
            'user_id' => $user->id,
            'employee_number' => 'EMP-001',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => $user->email,
            'hire_date' => now()->subYear(),
            'profile_confirmed_at' => now(),
        ]);

        return [$user, $this->createPayslipForEmployee($employee)];
    }

    private function createPayslipForEmployee(Employee $employee): Payslip
    {
        $run = PayrollRun::create([
            'name' => 'June 2026',
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
            'payment_date' => now()->endOfMonth(),
            'status' => 'locked',
        ]);

        return Payslip::create([
            'payroll_run_id' => $run->id,
            'employee_id' => $employee->id,
            'gross_pay' => 1000,
            'net_pay' => 800,
        ]);
    }
}
