@extends('layouts.adminBase')

@section('content')
@include('admin.includes.sidebar')
<div class="content">
    @include('admin.includes.navbar')
    <div class="container-fluid pt-4 px-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="mb-1">Homepage marketing</h4>
                <p class="text-muted small mb-0">Hero copy, value band, and pillar cards on the <a href="{{ route('home') }}" target="_blank">homepage</a>.</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScenarioModal">
                <i class="fa fa-plus me-1"></i> Add pillar
            </button>
        </div>

        <div class="bg-light rounded p-4 mb-4">
            <h5 class="mb-3">Hero (left column)</h5>
            <form action="{{ route('admin.home-hire.intro.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Eyebrow</label>
                        <input type="text" name="eyebrow" class="form-control" value="{{ old('eyebrow', $intro->eyebrow) }}" required>
                        <small class="text-muted">e.g. Car hire in Kigali</small>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Headline</label>
                        <input type="text" name="headline" class="form-control" value="{{ old('headline', $intro->headline) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Hook (one short paragraph)</label>
                        <textarea name="hook" class="form-control" rows="3" required>{{ old('hook', $intro->hook) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bold phrase inside hook</label>
                        <input type="text" name="hook_highlight" class="form-control" value="{{ old('hook_highlight', $intro->hook_highlight) }}" placeholder="Kigali Drive Rentals">
                        <small class="text-muted">Must match text in the hook exactly.</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Primary button</label>
                        <input type="text" name="cta_primary_label" class="form-control" value="{{ old('cta_primary_label', $intro->cta_primary_label) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Primary URL</label>
                        <input type="text" name="cta_primary_url" class="form-control" value="{{ old('cta_primary_url', $intro->cta_primary_url) }}" required placeholder="/cars">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Secondary button</label>
                        <input type="text" name="cta_secondary_label" class="form-control" value="{{ old('cta_secondary_label', $intro->cta_secondary_label) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Secondary URL</label>
                        <input type="text" name="cta_secondary_url" class="form-control" value="{{ old('cta_secondary_url', $intro->cta_secondary_url) }}" placeholder="/contact">
                    </div>
                    <div class="col-12"><hr class="my-1"><h6 class="text-muted mb-2">Value band (below hero)</h6></div>
  
                    <div class="col-md-8">
                        <label class="form-label">Band headline</label>
                        <input type="text" name="section_title" class="form-control" value="{{ old('section_title', $intro->section_title) }}" placeholder="Rent with confidence. Drive Rwanda your way.">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Band lead line</label>
                        <textarea name="section_lead" class="form-control" rows="2" placeholder="One sentence on your edge in the market.">{{ old('section_lead', $intro->section_lead) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="show_on_hero" value="1" id="show_on_hero" @checked(old('show_on_hero', $intro->show_on_hero))>
                            <label class="form-check-label" for="show_on_hero">Show value band on homepage</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="intro_active" @checked(old('is_active', $intro->is_active))>
                            <label class="form-check-label" for="intro_active">Homepage marketing active</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Save homepage copy</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-light rounded p-4">
            <h5 class="mb-3">Value pillars (4–6 recommended)</h5>
            <p class="text-muted small">Icon: Font Awesome name, e.g. <code>fa-plane-arrival</code> or <code>fas fa-car</code></p>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px">Order</th>
                            <th style="width:50px">Icon</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th style="width:90px">Status</th>
                            <th style="width:150px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scenarios as $scenario)
                        <tr>
                            <td>{{ $scenario->sort_order }}</td>
                            <td><i class="{{ $scenario->iconClass() }}" title="{{ $scenario->icon }}"></i></td>
                            <td><strong>{{ $scenario->title }}</strong></td>
                            <td class="text-muted small">{{ $scenario->description }}</td>
                            <td>
                                @if($scenario->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Hidden</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editScenario{{ $scenario->id }}">Edit</button>
                                <form action="{{ route('admin.home-hire.scenarios.destroy', $scenario) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this scenario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No scenarios yet. Run <code>php artisan db:seed --class=HomeHireSeeder</code> or add one above.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addScenarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.home-hire.scenarios.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add scenario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.home-hire.partials.scenario-fields')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($scenarios as $scenario)
<div class="modal fade" id="editScenario{{ $scenario->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.home-hire.scenarios.update', $scenario) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit scenario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.home-hire.partials.scenario-fields', ['scenario' => $scenario])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@include('admin.includes.footer')
@endsection
