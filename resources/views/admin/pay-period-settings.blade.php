<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Pay Period Settings</h2></x-slot>
    <div class="page-shell">
        <div class="container-app max-w-2xl">
            <x-flash-messages />
            <div class="card card-body">
                <form method="POST" action="{{ route('admin.pay-period-settings.update') }}" class="space-y-5">
                    @csrf @method('PUT')
                    <div>
                        <label class="form-label" for="frequency">Pay Period Frequency</label>
                        <select name="frequency" id="frequency" class="form-select" required>
                            @foreach ($frequencies as $freq)
                                <option value="{{ $freq->value }}" {{ old('frequency', $settings->frequency->value) === $freq->value ? 'selected' : '' }}>{{ $freq->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="overtime_threshold_hours">Weekly Overtime Threshold (hours)</label>
                        <input type="number" name="overtime_threshold_hours" id="overtime_threshold_hours" value="{{ old('overtime_threshold_hours', $settings->overtime_threshold_hours) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label" for="default_overtime_multiplier">Default Overtime Multiplier</label>
                        <input type="number" step="0.1" name="default_overtime_multiplier" id="default_overtime_multiplier" value="{{ old('default_overtime_multiplier', $settings->default_overtime_multiplier) }}" class="form-input" required>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary">Save Settings</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
