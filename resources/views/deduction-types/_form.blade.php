@php
    $categories = \App\Enums\DeductionCategory::cases();
    $calcTypes = \App\Enums\CalculationType::cases();
@endphp

<div class="space-y-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="form-label" for="name">Name *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $deductionType?->name) }}" class="form-input" required>
            @error('name') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label" for="code">Code *</label>
            <input type="text" name="code" id="code" value="{{ old('code', $deductionType?->code) }}" class="form-input" required>
            @error('code') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label" for="category">Category *</label>
            <select name="category" id="category" class="form-select" required>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->value }}" {{ old('category', $deductionType?->category?->value) === $cat->value ? 'selected' : '' }}>{{ ucfirst($cat->value) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label" for="calculation_type">Calculation Type *</label>
            <select name="calculation_type" id="calculation_type" class="form-select" required>
                @foreach ($calcTypes as $calc)
                    <option value="{{ $calc->value }}" {{ old('calculation_type', $deductionType?->calculation_type?->value) === $calc->value ? 'selected' : '' }}>{{ ucfirst($calc->value) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label" for="default_amount">Default Fixed Amount</label>
            <input type="number" step="0.01" name="default_amount" id="default_amount" value="{{ old('default_amount', $deductionType?->default_amount) }}" class="form-input">
        </div>
        <div>
            <label class="form-label" for="default_rate">Default Rate (%)</label>
            <input type="number" step="0.01" name="default_rate" id="default_rate" value="{{ old('default_rate', $deductionType?->default_rate) }}" class="form-input">
        </div>
    </div>
    <div>
        <label class="form-label" for="description">Description</label>
        <textarea name="description" id="description" rows="3" class="form-input">{{ old('description', $deductionType?->description) }}</textarea>
    </div>
    <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" class="rounded border-outline-variant text-primary focus:ring-primary" {{ old('is_active', $deductionType?->is_active ?? true) ? 'checked' : '' }}>
        <span class="text-body-sm">Active</span>
    </label>
</div>
