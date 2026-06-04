@php $scenario = $scenario ?? null; @endphp
<div class="mb-3">
    <label class="form-label">Icon</label>
    <input type="text" name="icon" class="form-control" value="{{ old('icon', $scenario->icon ?? 'fa-car') }}" required placeholder="fa-plane-arrival">
</div>
<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $scenario->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Short description</label>
    <input type="text" name="description" class="form-control" value="{{ old('description', $scenario->description ?? '') }}" required maxlength="255">
</div>
<div class="mb-3">
    <label class="form-label">Sort order</label>
    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $scenario->sort_order ?? 0) }}" min="0">
</div>
<div class="form-check">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="scenario_active_{{ $scenario->id ?? 'new' }}" @checked(old('is_active', $scenario->is_active ?? true))>
    <label class="form-check-label" for="scenario_active_{{ $scenario->id ?? 'new' }}">Active</label>
</div>
