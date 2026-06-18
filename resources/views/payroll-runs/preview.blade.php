<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Preview: {{ $payrollRun->name }}</h2></x-slot>
    <div class="page-shell">
        <div class="container-app space-y-6">
            <x-flash-messages />

            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2 text-body-md">
                    <p>Period: <strong>{{ $payrollRun->period_start->format('d M Y') }}</strong> – <strong>{{ $payrollRun->period_end->format('d M Y') }}</strong></p>
                    <p>Status: <x-status-badge :status="$payrollRun->status" />
                        @if ($payrollRun->is_approved) <span class="chip-success ml-2">Approved</span> @endif
                    </p>
                    <p class="text-body-sm text-on-surface-variant">Preview only — no payslips are saved until you process.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if ($payrollRun->status->value !== 'locked' && count($previews) > 0)
                        <form method="POST" action="{{ route('payroll-runs.process', $payrollRun) }}" id="process-all-form" onsubmit="return confirm('Process payroll for all eligible employees shown below?')">
                            @csrf
                            <button type="submit" class="btn-primary">Process All</button>
                        </form>
                    @endif
                    <a href="{{ route('payroll-runs.show', $payrollRun) }}" class="btn-secondary">View Run</a>
                    <a href="{{ route('payroll-runs.index') }}" class="btn-secondary">Back</a>
                </div>
            </div>

            @if (count($previews) === 0)
                <div class="card card-body text-center text-on-surface-variant py-12">
                    No eligible employees found for this pay period.
                </div>
            @else
                <form method="POST" action="{{ route('payroll-runs.process', $payrollRun) }}" id="process-selected-form">
                    @csrf
                    <input type="hidden" name="require_selection" value="1">
                    <div class="card overflow-hidden">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-outline-variant px-4 py-3 bg-surface-container-low">
                            <p class="text-body-sm text-on-surface-variant">
                                <strong>{{ count($previews) }}</strong> eligible {{ Str::plural('employee', count($previews)) }}
                            </p>
                            <button type="submit" class="btn-secondary" onclick="return confirm('Process payroll for selected employees only?')">
                                Process Selected
                            </button>
                        </div>
                        <table class="table-list">
                            <thead>
                                <tr>
                                    <th class="w-10">
                                        <input type="checkbox" id="select-all" class="rounded border-outline-variant text-primary focus:ring-primary" checked>
                                    </th>
                                    <th>Employee</th>
                                    <th class="text-right">Gross</th>
                                    <th class="text-right">Tax</th>
                                    <th class="text-right">Deductions</th>
                                    <th class="text-right">Net Pay</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($previews as $preview)
                                    @php $employee = $preview['employee']; @endphp
                                    <tr>
                                        <td>
                                            <input
                                                type="checkbox"
                                                name="employee_ids[]"
                                                value="{{ $employee->id }}"
                                                class="employee-checkbox rounded border-outline-variant text-primary focus:ring-primary"
                                                checked
                                            >
                                        </td>
                                        <td>
                                            <p class="font-medium">{{ $employee->fullName() }}</p>
                                            <p class="text-body-sm text-on-surface-variant">
                                                {{ $employee->employee_number }}
                                                @if ($preview['is_prorated'])
                                                    · <span class="text-amber-700">{{ $preview['proration_note'] }}</span>
                                                @endif
                                                @if ($processedEmployeeIds->contains($employee->id))
                                                    · <span class="chip-warning">Already processed</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td class="text-right">{{ number_format($preview['gross_pay'], 2) }}</td>
                                        <td class="text-right text-error">{{ number_format($preview['total_tax'], 2) }}</td>
                                        <td class="text-right text-error">{{ number_format($preview['total_deductions'], 2) }}</td>
                                        <td class="text-right font-semibold text-primary">{{ number_format($preview['net_pay'], 2) }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('payroll-runs.preview-employee', [$payrollRun, $employee]) }}" class="link-primary">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-surface-container-low font-semibold">
                                <tr>
                                    <td></td>
                                    <td>Totals</td>
                                    <td class="text-right">{{ number_format(collect($previews)->sum('gross_pay'), 2) }}</td>
                                    <td class="text-right">{{ number_format(collect($previews)->sum('total_tax'), 2) }}</td>
                                    <td class="text-right">{{ number_format(collect($previews)->sum('total_deductions'), 2) }}</td>
                                    <td class="text-right">{{ number_format(collect($previews)->sum('net_pay'), 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('select-all')?.addEventListener('change', function () {
            document.querySelectorAll('.employee-checkbox').forEach(cb => cb.checked = this.checked);
        });
    </script>
</x-app-layout>
