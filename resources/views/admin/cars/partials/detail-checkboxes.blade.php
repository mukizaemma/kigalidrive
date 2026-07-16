@php
    $carDetails = $carDetails ?? collect();
    $selectedDetailIds = collect(old('detail_ids', $selectedDetailIds ?? []))->map(fn ($id) => (int) $id)->all();
@endphp
<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
            <label class="form-label mb-0">Extra details</label>
            <a href="{{ route('admin.car-details.index') }}" class="small" target="_blank">Manage details</a>
        </div>
        @if($carDetails->isEmpty())
            <p class="text-muted small mb-0">No active details yet. <a href="{{ route('admin.car-details.index') }}">Add some</a> to assign them here.</p>
        @else
            <div class="row g-2">
                @foreach($carDetails as $detail)
                <div class="col-md-6 col-lg-4">
                    <div class="form-check border rounded px-3 py-2 h-100">
                        <input class="form-check-input" type="checkbox"
                               name="detail_ids[]"
                               id="{{ ($idPrefix ?? 'car') }}_detail_{{ $detail->id }}"
                               value="{{ $detail->id }}"
                               @checked(in_array($detail->id, $selectedDetailIds, true))>
                        <label class="form-check-label" for="{{ ($idPrefix ?? 'car') }}_detail_{{ $detail->id }}">
                            <i class="{{ $detail->iconClass() }} me-1 text-muted"></i>{{ $detail->name }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            <small class="text-muted d-block mt-2">Only checked items appear on the public car listing.</small>
        @endif
        @error('detail_ids')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        @error('detail_ids.*')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>
