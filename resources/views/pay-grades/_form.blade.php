<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div>
        <label class="form-label" for="name">Name *</label>
        <input type="text" name="name" id="name" value="{{ old('name', $payGrade->name ?? '') }}" class="form-input" required>
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="code">Code *</label>
        <input type="text" name="code" id="code" value="{{ old('code', $payGrade->code ?? '') }}" class="form-input" required>
        @error('code') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="base_salary">Base Salary *</label>
        <input type="number" step="0.01" name="base_salary" id="base_salary" value="{{ old('base_salary', $payGrade->base_salary ?? '') }}" class="form-input" required>
        @error('base_salary') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="currency">Currency *</label>
        <input type="text" name="currency" id="currency" value="{{ old('currency', $payGrade->currency ?? 'NGN') }}" maxlength="3" class="form-input" required>
    </div>
    <div class="sm:col-span-2">
        <label class="form-label" for="description">Description</label>
        <textarea name="description" id="description" rows="2" class="form-input">{{ old('description', $payGrade->description ?? '') }}</textarea>
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-outline-variant text-primary focus:ring-primary" {{ old('is_active', $payGrade->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-body-md text-on-surface">Active</label>
    </div>
</div>
