@php
    $guarantorOne = $employee->guarantors->firstWhere('slot', 1);
    $guarantorTwo = $employee->guarantors->firstWhere('slot', 2);
@endphp

<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">My Profile</h2></x-slot>
    <div class="page-shell">
        <div class="container-app max-w-3xl space-y-6">
            <x-flash-messages />

            @unless ($employee->profile_confirmed_at)
                <div class="card card-body border-primary/30 bg-primary-fixed/20">
                    <h3 class="font-heading text-h3 text-on-background mb-2">Complete your onboarding</h3>
                    <p class="text-body-md text-on-surface-variant mb-4">
                        Review your employment and bank details, add <strong>two guarantors</strong>, then confirm your profile to access the full portal.
                    </p>
                    @if ($employee->hasCompleteGuarantors())
                        <form method="POST" action="{{ route('ess.profile.confirm') }}">
                            @csrf
                            <button type="submit" class="btn-primary">Confirm Profile Details</button>
                        </form>
                    @else
                        <p class="text-body-sm text-on-surface-variant">Save both guarantor forms below before confirming your profile.</p>
                    @endif
                </div>
            @endunless

            <div class="card card-body">
                <x-page-header title="Employment Details" />
                <dl class="grid grid-cols-2 gap-4 text-body-md">
                    <div><dt class="text-on-surface-variant">Name</dt><dd class="font-medium">{{ $employee->fullName() }}</dd></div>
                    <div><dt class="text-on-surface-variant">Department</dt><dd>{{ $employee->department?->name ?? '—' }}</dd></div>
                    <div><dt class="text-on-surface-variant">Job Title</dt><dd>{{ $employee->job_title ?? '—' }}</dd></div>
                    <div><dt class="text-on-surface-variant">Hire Date</dt><dd>{{ $employee->hire_date->format('d M Y') }}</dd></div>
                    <div><dt class="text-on-surface-variant">Employment Type</dt><dd><x-status-badge :status="$employee->employment_type" /></dd></div>
                    <div><dt class="text-on-surface-variant">Compensation</dt><dd><x-status-badge :status="$employee->compensation_type" /></dd></div>
                </dl>
            </div>

            <div class="card card-body">
                <x-page-header title="Direct Deposit & Tax" subtitle="Securely update your banking details" />
                <form method="POST" action="{{ route('ess.profile.bank') }}" class="space-y-5">
                    @csrf @method('PATCH')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label" for="bank_name">Bank Name</label>
                            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $employee->bank_name) }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label" for="bank_account_number">Account Number</label>
                            <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number', $employee->bank_account_number) }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label" for="bank_routing_number">Routing Number</label>
                            <input type="text" name="bank_routing_number" id="bank_routing_number" value="{{ old('bank_routing_number', $employee->bank_routing_number) }}" class="form-input">
                        </div>
                        <div>
                            <label class="form-label" for="tax_id">Tax ID</label>
                            <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $employee->tax_id) }}" class="form-input">
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </form>
            </div>

            <div class="card card-body">
                <x-page-header title="Guarantors" subtitle="Two guarantors are required as part of your onboarding" />
                <form method="POST" action="{{ route('ess.profile.guarantors') }}" class="space-y-6">
                    @csrf
                    @foreach ([1 => $guarantorOne, 2 => $guarantorTwo] as $slot => $guarantor)
                        @php $locked = $guarantor?->isConfirmed(); @endphp
                        <div class="rounded-lg border border-outline-variant p-5 space-y-4 {{ $locked ? 'bg-surface-container-low opacity-80' : '' }}">
                            <div class="flex items-center justify-between gap-3">
                                <h4 class="font-heading text-body-md font-bold text-on-surface">Guarantor {{ $slot }}</h4>
                                @if ($guarantor)
                                    <x-status-badge :status="$guarantor->status->value" />
                                @endif
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="form-label" for="guarantor_{{ $slot }}_name">Full Name *</label>
                                    <input type="text" name="guarantors[{{ $slot }}][full_name]" id="guarantor_{{ $slot }}_name" value="{{ old("guarantors.{$slot}.full_name", $guarantor?->full_name) }}" class="form-input" {{ $locked ? 'readonly' : 'required' }}>
                                    @error("guarantors.{$slot}.full_name") <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="form-label" for="guarantor_{{ $slot }}_email">Email *</label>
                                    <input type="email" name="guarantors[{{ $slot }}][email]" id="guarantor_{{ $slot }}_email" value="{{ old("guarantors.{$slot}.email", $guarantor?->email) }}" class="form-input" {{ $locked ? 'readonly' : 'required' }}>
                                    @error("guarantors.{$slot}.email") <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="form-label" for="guarantor_{{ $slot }}_phone">Phone Number *</label>
                                    <input type="text" name="guarantors[{{ $slot }}][phone]" id="guarantor_{{ $slot }}_phone" value="{{ old("guarantors.{$slot}.phone", $guarantor?->phone) }}" class="form-input" {{ $locked ? 'readonly' : 'required' }}>
                                    @error("guarantors.{$slot}.phone") <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="form-label" for="guarantor_{{ $slot }}_address">Address *</label>
                                    <textarea name="guarantors[{{ $slot }}][address]" id="guarantor_{{ $slot }}_address" rows="2" class="form-input" {{ $locked ? 'readonly' : 'required' }}>{{ old("guarantors.{$slot}.address", $guarantor?->address) }}</textarea>
                                    @error("guarantors.{$slot}.address") <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            @if ($locked)
                                <p class="text-body-sm text-on-surface-variant">Confirmed by admin on {{ $guarantor->confirmed_at->format('d M Y') }}. Contact HR to request changes.</p>
                            @endif
                        </div>
                    @endforeach
                    @if (! $guarantorOne?->isConfirmed() || ! $guarantorTwo?->isConfirmed())
                        <button type="submit" class="btn-primary">Save Guarantors</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
