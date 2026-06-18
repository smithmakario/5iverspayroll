<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Employee Profile</h2></x-slot>
    <div class="page-shell">
        <div class="container-app space-y-6">
            <x-flash-messages />
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    @if ($employee->user)
                        <x-user-avatar :user="$employee->user" size="md" />
                    @endif
                    <div>
                        <h1 class="text-h3">{{ $employee->fullName() }}</h1>
                        <p class="text-body-sm text-on-surface-variant">{{ $employee->employee_number }} &bull; {{ $employee->job_title ?? 'No title' }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if ($employee->user)
                        <span class="{{ $employee->profile_confirmed_at ? 'chip-success' : 'chip-warning' }}">
                            Portal: {{ $employee->profile_confirmed_at ? 'Active' : 'Pending confirmation' }}
                        </span>
                        @if ($employee->guarantors->isNotEmpty())
                            <span class="{{ $employee->allGuarantorsConfirmed() ? 'chip-success' : 'chip-warning' }}">
                                Guarantors: {{ $employee->guarantors->where('status', \App\Enums\GuarantorStatus::Confirmed)->count() }}/2 confirmed
                            </span>
                        @endif
                        <form method="POST" action="{{ route('employees.resend-onboarding', $employee) }}" class="inline">
                            @csrf
                            <button type="submit" class="btn-secondary">Resend Onboarding Email</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('employees.resend-onboarding', $employee) }}" class="inline">
                            @csrf
                            <button type="submit" class="btn-secondary">Send Onboarding Email</button>
                        </form>
                    @endif
                    <a href="{{ route('employees.deductions.index', $employee) }}" class="btn-secondary">Manage Deductions</a>
                    <a href="{{ route('employees.earnings.index', $employee) }}" class="btn-secondary">Bonuses & Commissions</a>
                    <a href="{{ route('employees.edit', $employee) }}" class="btn-primary">Edit</a>
                    <a href="{{ route('employees.index') }}" class="btn-secondary">Back</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card card-body space-y-3">
                    <h3 class="font-heading text-h3 border-b border-outline-variant pb-2">Employment</h3>
                    <dl class="space-y-2 text-body-md">
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Department</dt><dd class="font-medium">{{ $employee->department?->name ?? '—' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Location</dt><dd class="font-medium">{{ $employee->location?->name ?? '—' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Pay Grade</dt><dd>{{ $employee->payGrade?->name ?? '—' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Employment Type</dt><dd><x-status-badge :status="$employee->employment_type ?? 'full_time'" /></dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Compensation</dt><dd><x-status-badge :status="$employee->compensation_type ?? 'salary'" /></dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Base Salary</dt><dd>{{ $employee->base_salary ? number_format($employee->base_salary, 2) : ($employee->payGrade ? number_format($employee->payGrade->base_salary, 2) : '—') }}</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Hourly Rate</dt><dd>{{ $employee->hourly_rate ? number_format($employee->hourly_rate, 2) : '—' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Hire Date</dt><dd>{{ $employee->hire_date->format('d M Y') }}</dd></div>
                        @if ($employee->termination_date)
                            <div class="flex justify-between"><dt class="text-on-surface-variant">Termination Date</dt><dd>{{ $employee->termination_date->format('d M Y') }}</dd></div>
                        @endif
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Status</dt><dd><x-status-badge :status="$employee->employment_status" /></dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">PTO Balance</dt><dd>{{ number_format($employee->pto_balance ?? 0, 1) }} days</dd></div>
                    </dl>
                </div>

                <div class="card card-body space-y-3">
                    <h3 class="font-heading text-h3 border-b border-outline-variant pb-2">Direct Deposit</h3>
                    <dl class="space-y-2 text-body-md">
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Email</dt><dd>{{ $employee->email }}</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Phone</dt><dd>{{ $employee->phone ?? '—' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Bank</dt><dd>{{ $employee->bank_name ?? '—' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Account No.</dt><dd>{{ $employee->bank_account_number ?? '—' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Routing No.</dt><dd>{{ $employee->bank_routing_number ?? '—' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-on-surface-variant">Tax ID</dt><dd>{{ $employee->tax_id ?? '—' }}</dd></div>
                    </dl>
                </div>
            </div>

            <div class="card overflow-hidden">
                <div class="card-body pb-0">
                    <x-page-header title="Guarantors" subtitle="Submitted during employee onboarding — admin confirmation required" />
                </div>
                @if ($employee->guarantors->isEmpty())
                    <div class="card-body pt-0 text-on-surface-variant text-body-sm">No guarantors submitted yet.</div>
                @else
                    <table class="table-list">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->guarantors as $guarantor)
                                <tr>
                                    <td>{{ $guarantor->slot }}</td>
                                    <td class="font-medium">{{ $guarantor->full_name }}</td>
                                    <td>
                                        <div>{{ $guarantor->email }}</div>
                                        <div class="text-body-sm text-on-surface-variant">{{ $guarantor->phone }}</div>
                                    </td>
                                    <td class="max-w-xs">{{ $guarantor->address }}</td>
                                    <td>
                                        <x-status-badge :status="$guarantor->status->value" />
                                        @if ($guarantor->confirmed_at)
                                            <div class="text-body-sm text-on-surface-variant mt-1">{{ $guarantor->confirmed_at->format('d M Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @role(\App\Enums\UserRole::Admin->value)
                                            @unless ($guarantor->isConfirmed())
                                                <a href="{{ route('employees.guarantors.confirm', [$employee, $guarantor]) }}" class="btn-primary text-body-sm py-2 px-3 inline-flex">
                                                    Confirm
                                                </a>
                                            @else
                                                <span class="text-body-sm text-on-surface-variant">By {{ $guarantor->confirmer?->name ?? 'Admin' }}</span>
                                            @endunless
                                        @endrole
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            @if ($employee->earnings->isNotEmpty())
                <div class="card overflow-hidden">
                    <div class="card-body pb-0"><h3 class="font-heading text-h3">Bonuses & Commissions</h3></div>
                    <table class="table-list">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Amount / Rate</th>
                                <th>Effective</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->earnings as $earning)
                                <tr>
                                    <td>{{ $earning->earningType->name }}</td>
                                    <td>{{ $earning->earningType->category->label() }}</td>
                                    <td>
                                        @if ($earning->amount) {{ number_format($earning->amount, 2) }}
                                        @elseif ($earning->rate) {{ $earning->rate }}%
                                        @else — @endif
                                    </td>
                                    <td>{{ $earning->effective_from?->format('d M Y') ?? '—' }}</td>
                                    <td><x-status-badge :status="$earning->is_active ? 'active' : 'terminated'" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($employee->deductions->isNotEmpty())
                <div class="card overflow-hidden">
                    <div class="card-body pb-0"><h3 class="font-heading text-h3">Assigned Deductions</h3></div>
                    <table class="table-list">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Amount / Rate</th>
                                <th>Effective</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->deductions as $ded)
                                <tr>
                                    <td>{{ $ded->deductionType->name }}</td>
                                    <td class="capitalize">{{ $ded->deductionType->category->value }}</td>
                                    <td>
                                        @if ($ded->amount) {{ number_format($ded->amount, 2) }}
                                        @elseif ($ded->rate) {{ $ded->rate }}%
                                        @else — @endif
                                    </td>
                                    <td>{{ $ded->effective_from?->format('d M Y') ?? '—' }}</td>
                                    <td><x-status-badge :status="$ded->is_active ? 'active' : 'terminated'" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
