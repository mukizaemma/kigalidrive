@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')
<div class="content">
    @include('admin.includes.navbar')
    <div class="container-fluid pt-4 px-4">
        <h4 class="mb-3">Car rental agreement template</h4>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form method="POST" action="{{ route('admin.rental-agreement.update') }}" class="bg-white rounded p-4">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Platform name</label>
                <input type="text" name="platform_name" class="form-control" value="{{ old('platform_name', $template->platform_name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Intro</label>
                <textarea name="intro_text" class="form-control" rows="4">{{ old('intro_text', $template->intro_text) }}</textarea>
            </div>
            @php $sections = old('sections', $template->sections ?? []); @endphp
            @foreach($sections as $i => $section)
            <div class="border rounded p-3 mb-3">
                <input type="text" name="sections[{{ $i }}][heading]" class="form-control mb-2" value="{{ $section['heading'] ?? '' }}" placeholder="Section heading">
                <textarea name="sections[{{ $i }}][items][0]" class="form-control" rows="2">{{ is_array($section['items'] ?? null) ? ($section['items'][0] ?? '') : '' }}</textarea>
            </div>
            @endforeach
            <a href="{{ route('admin.rental-agreement.preview') }}" target="_blank" class="btn btn-outline-secondary me-2">Preview / Print PDF</a>
            <button type="submit" class="btn btn-primary">Save template</button>
        </form>
    </div>
</div>
@endsection
