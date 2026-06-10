<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
        <label class="form-label" for="employee_id">Employee *</label>
        <select name="employee_id" id="employee_id" class="form-select" required>
            <option value="">— Select —</option>
            @foreach ($employees as $emp)
                <option value="{{ $emp->id }}" {{ old('employee_id', $attendance->employee_id ?? '') == $emp->id ? 'selected' : '' }}>{{ $emp->fullName() }}</option>
            @endforeach
        </select>
        @error('employee_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="date">Date *</label>
        <input type="date" name="date" id="date" value="{{ old('date', isset($attendance) ? $attendance->date->format('Y-m-d') : '') }}" class="form-input" required>
        @error('date') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="status">Status *</label>
        <select name="status" id="status" class="form-select" required>
            @foreach ($statuses as $s)
                <option value="{{ $s->value }}" {{ old('status', $attendance->status->value ?? '') === $s->value ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $s->value)) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label" for="clock_in">Clock In</label>
        <input type="time" name="clock_in" id="clock_in" value="{{ old('clock_in', $attendance->clock_in ?? '') }}" class="form-input">
    </div>
    <div>
        <label class="form-label" for="clock_out">Clock Out</label>
        <input type="time" name="clock_out" id="clock_out" value="{{ old('clock_out', $attendance->clock_out ?? '') }}" class="form-input">
    </div>
    <div>
        <label class="form-label" for="break_minutes">Break (minutes)</label>
        <input type="number" name="break_minutes" id="break_minutes" min="0" value="{{ old('break_minutes', $attendance->break_minutes ?? 0) }}" class="form-input">
    </div>
    <div>
        <label class="form-label" for="notes">Notes</label>
        <textarea name="notes" id="notes" rows="2" class="form-input">{{ old('notes', $attendance->notes ?? '') }}</textarea>
    </div>
</div>
