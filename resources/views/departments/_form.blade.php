<div class="space-y-5">
    <div>
        <label class="form-label" for="name">Name *</label>
        <input type="text" name="name" id="name" value="{{ old('name', $department->name ?? '') }}" class="form-input" required>
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="code">Code *</label>
        <input type="text" name="code" id="code" value="{{ old('code', $department->code ?? '') }}" class="form-input" required>
        @error('code') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="form-label" for="description">Description</label>
        <textarea name="description" id="description" rows="2" class="form-input">{{ old('description', $department->description ?? '') }}</textarea>
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-outline-variant text-primary focus:ring-primary" {{ old('is_active', $department->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="text-body-md text-on-surface">Active</label>
    </div>
</div>
