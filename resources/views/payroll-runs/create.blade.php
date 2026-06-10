<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">New Payroll Run</h2></x-slot>
    <div class="page-shell">
        <div class="container-app max-w-2xl">
            <x-flash-messages />

            @if ($activeEmployeeCount === 0)
                <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-body-sm text-amber-900">
                    No active employees found. Payroll cannot be processed until at least one employee is active.
                </div>
            @else
                <div class="mb-4 rounded-lg border border-primary/20 bg-primary-container/30 p-4 text-body-sm text-on-surface">
                    <strong>{{ $activeEmployeeCount }}</strong> active {{ Str::plural('employee', $activeEmployeeCount) }} will be included when payroll is processed.
                </div>
            @endif

            <div class="card card-body">
                <x-page-header title="Create Payroll Run" subtitle="Set the pay period, then run payroll or save as a draft for review" />

                <div class="mb-6 rounded-lg border border-outline-variant bg-surface-container-low p-4 text-body-sm text-on-surface-variant">
                    <p class="font-medium text-on-surface">How payroll runs work</p>
                    <ol class="mt-2 list-decimal space-y-1 pl-5">
                        <li>Create the run with the pay period dates.</li>
                        <li>Approve the run (or check <strong>Run payroll immediately</strong> below).</li>
                        <li>Process payroll to generate payslips for all active employees.</li>
                    </ol>
                </div>

                <form method="POST" action="{{ route('payroll-runs.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="form-label" for="name">Run Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. June 2026 Payroll" class="form-input" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="form-label" for="period_start">Period Start *</label>
                            <input type="date" name="period_start" id="period_start" value="{{ old('period_start', now()->startOfMonth()->toDateString()) }}" class="form-input" required>
                            @error('period_start') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label" for="period_end">Period End *</label>
                            <input type="date" name="period_end" id="period_end" value="{{ old('period_end', now()->endOfMonth()->toDateString()) }}" class="form-input" required>
                            @error('period_end') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="form-label" for="payment_date">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date') }}" class="form-input">
                        <p class="mt-1 text-body-sm text-on-surface-variant">If set, must be on or after the period end date.</p>
                        @error('payment_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="notes">Notes</label>
                        <textarea name="notes" id="notes" rows="2" class="form-input">{{ old('notes') }}</textarea>
                        @error('notes') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-start gap-3 rounded-lg border border-outline-variant p-4">
                        <input
                            type="checkbox"
                            name="process_now"
                            id="process_now"
                            value="1"
                            class="mt-1 rounded border-outline text-primary focus:ring-primary"
                            @checked(old('process_now', true))
                            @disabled($activeEmployeeCount === 0)
                        >
                        <label for="process_now" class="text-body-sm">
                            <span class="font-medium text-on-surface">Run payroll immediately</span>
                            <span class="mt-1 block text-on-surface-variant">Approve and process this run now, generating payslips for all active employees.</span>
                        </label>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary">
                            {{ old('process_now', true) ? 'Create & Run Payroll' : 'Create Run' }}
                        </button>
                        <a href="{{ route('payroll-runs.index') }}" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
