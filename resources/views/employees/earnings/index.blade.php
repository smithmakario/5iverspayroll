<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">{{ $employee->fullName() }} — Bonuses & Commissions</h2></x-slot>
    <div class="page-shell">
        <div class="container-app space-y-6">
            <x-flash-messages />
            <x-page-header title="Employee Earnings" subtitle="Assign bonuses, commissions, and allowances for payroll" />

            <div class="card card-body max-w-3xl">
                <form method="POST" action="{{ route('employees.earnings.store', $employee) }}" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="form-label" for="earning_type_id">Earning Type</label>
                            <select name="earning_type_id" id="earning_type_id" class="form-select" required>
                                <option value="">— Select —</option>
                                @foreach ($earningTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }}) — {{ $type->category->label() }}</option>
                                @endforeach
                            </select>
                            @error('earning_type_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label" for="amount">Fixed Amount</label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-input">
                            @error('amount') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label" for="rate">Rate (% of base pay)</label>
                            <input type="number" step="0.01" name="rate" id="rate" class="form-input">
                            @error('rate') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label" for="effective_from">Effective From</label>
                            <input type="date" name="effective_from" id="effective_from" class="form-input">
                            @error('effective_from') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label" for="effective_to">Effective To</label>
                            <input type="date" name="effective_to" id="effective_to" class="form-input">
                            @error('effective_to') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <p class="text-body-sm text-on-surface-variant">Leave amount/rate blank to use the earning type default. Percentage rates apply to base salary or hourly pay before bonuses.</p>
                    <button type="submit" class="btn-primary">Assign Earning</button>
                </form>
            </div>

            <div class="card overflow-hidden">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Amount / Rate</th>
                            <th>Effective</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employee->earnings as $earning)
                            <tr>
                                <td class="font-medium">{{ $earning->earningType->name }}</td>
                                <td>{{ $earning->earningType->category->label() }}</td>
                                <td>
                                    @if ($earning->amount) {{ number_format($earning->amount, 2) }}
                                    @elseif ($earning->rate) {{ $earning->rate }}%
                                    @else Default @endif
                                </td>
                                <td>{{ $earning->effective_from?->format('d M Y') ?? '—' }} – {{ $earning->effective_to?->format('d M Y') ?? '—' }}</td>
                                <td><x-status-badge :status="$earning->is_active ? 'active' : 'terminated'" /></td>
                                <td class="text-right">
                                    <form method="POST" action="{{ route('employees.earnings.destroy', ['employee' => $employee, 'employee_earning' => $earning]) }}" class="inline" onsubmit="return confirm('Remove this earning?')">
                                        @csrf
                                        <button class="text-error hover:underline">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-8 text-on-surface-variant">No bonuses or commissions assigned.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <a href="{{ route('employees.show', $employee) }}" class="btn-secondary inline-flex">Back to Employee</a>
        </div>
    </div>
</x-app-layout>
