@php
    $name = $name ?? 'description';
    $id = $id ?? $name;
    $label = $label ?? 'Description';
    $rows = $rows ?? 6;
    $value = $value ?? '';
    $height = $height ?? 220;
    $class = trim('form-control summernote ' . ($class ?? ''));
@endphp
<div class="{{ $wrapperClass ?? 'mb-3' }}">
    @if($label)
        <label for="{{ $id }}" class="form-label {{ $labelClass ?? '' }}">{{ $label }}</label>
    @endif
    <textarea
        name="{{ $name }}"
        id="{{ $id }}"
        rows="{{ $rows }}"
        class="{{ $class }}"
        data-height="{{ $height }}"
        @if(!empty($placeholder)) data-placeholder="{{ $placeholder }}" @endif
    >{!! old($name, $value) !!}</textarea>
    @if(!empty($hint))
        <small class="text-muted d-block mt-1">{{ $hint }}</small>
    @endif
    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
