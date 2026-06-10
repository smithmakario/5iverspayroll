<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">{{ $employee->fullName() }} — Deductions</h2></x-slot>
    <div class="page-shell">
        <div class="container-app space-y-6">
            <x-flash-messages />
            <x-page-header title="Employee Deductions" subtitle="Assign tax, statutory, and voluntary deductions" />

            <div class="card card-body max-w-3xl">
                <form method="POST" action="{{ route('employees.deductions.store', $employee) }}" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="form-label" for="deduction_type_id">Deduction Type</label>
                            <select name="deduction_type_id" id="deduction_type_id" class="form-select" required>
                                <option value="">— Select —</option>
                                @foreach ($deductionTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label" for="amount">Fixed Amount</label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-input">
                        </div>
                        <div>
                            <label class="form-label" for="rate">Rate (%)</label>
                            <input type="number" step="0.01" name="rate" id="rate" class="form-input">
                        </div>
                        <div>
                            <label class="form-label" for="effective_from">Effective From</label>
                            <input type="date" name="effective_from" id="effective_from" class="form-input">
                        </div>
                        <div>
                            <label class="form-label" for="effective_to">Effective To</label>
                            <input type="date" name="effective_to" id="effective_to" class="form-input">
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Assign Deduction</button>
                </form>
            </div>

            <div class="card overflow-hidden">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount / Rate</th>
                            <th>Effective</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employee->deductions as $deduction)
                            <tr>
                                <td class="font-medium">{{ $deduction->deductionType->name }}</td>
                                <td>
                                    @if ($deduction->amount) {{ number_format($deduction->amount, 2) }}
                                    @elseif ($deduction->rate) {{ $deduction->rate }}%
                                    @else Default @endif
                                </td>
                                <td>{{ $deduction->effective_from?->format('d M Y') ?? '—' }} – {{ $deduction->effective_to?->format('d M Y') ?? '—' }}</td>
                                <td><x-status-badge :status="$deduction->is_active ? 'active' : 'terminated'" /></td>
                                <td class="text-right">
                                    <form method="POST" action="{{ route('employees.deductions.destroy', [$employee, $deduction]) }}" class="inline" onsubmit="return confirm('Remove this deduction?')">
                                        @csrf @method('DELETE')
                                        <button class="text-error hover:underline">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-8 text-on-surface-variant">No deductions assigned.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('employees.show', $employee) }}" class="btn-secondary inline-flex">Back to Employee</a>
        </div>
    </div>
</x-app-layout>
