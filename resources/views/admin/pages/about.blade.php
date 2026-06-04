@extends('layouts.adminBase')

@section('content')
    @include('admin.includes.sidebar')
    <div class="content">
        @include('admin.includes.navbar')

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="btn btn-primary">About & Policies</h2>
                        </div>
                        <div class="card-body">
                            @if (session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session()->get('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @if (session()->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session()->get('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="about-tab" data-bs-toggle="tab" data-bs-target="#about-tab-pane" type="button" role="tab">About Us</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="site-images-tab" data-bs-toggle="tab" data-bs-target="#site-images-tab-pane" type="button" role="tab">Site Images</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms-tab-pane" type="button" role="tab">Terms &amp; Conditions</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-3">
                                <div class="tab-pane fade show active" id="about-tab-pane" role="tabpanel">
                                    <form action="{{ route('saveAbout') }}" method="POST" enctype="multipart/form-data" class="p-4 bg-light rounded">
                                        @csrf

                                        <h6 class="border-bottom pb-2 mb-3">Header & Title</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Header image</label>
                                            @if($data->image1)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/images/about/' . $data->image1) }}" alt="Header" width="280" class="rounded shadow-sm">
                                                </div>
                                            @endif
                                            <input type="file" name="image1" class="form-control" accept="image/*">
                                            <small class="text-muted">Leave empty to keep current image. Used as hero/header on the public About page.</small>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Page title</label>
                                                <input type="text" name="title" class="form-control" value="{{ old('title', $data->title) }}" placeholder="e.g. Stay Nets - About Us">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Tagline / Subtitle</label>
                                                <input type="text" name="subTitle" class="form-control" value="{{ old('subTitle', $data->subTitle) }}" placeholder="e.g. Your Trusted Partner for Travel, Stays, and Adventures in Rwanda & East Africa">
                                            </div>
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3 mt-4">Intro</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Welcome / Introduction</label>
                                            <textarea name="welcomeMessage" id="aboutDescription" rows="5" class="form-control summernote">{!! old('welcomeMessage', $data->welcomeMessage) !!}</textarea>
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3 mt-4">Mission & Vision</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Our Mission</label>
                                            <textarea name="mission" id="mission" class="form-control summernote" rows="4" placeholder="To provide exceptional travel services...">{!! old('mission', $data->mission) !!}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Our Vision</label>
                                            <textarea name="vision" id="vision" class="form-control summernote" rows="4" placeholder="To be the leading one-stop travel...">{!! old('vision', $data->vision ?? '') !!}</textarea>
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3 mt-4">What We Do</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">What We Do (services list – you can use bullet points)</label>
                                            <textarea name="what_we_do" id="whatWeDo" class="form-control summernote" rows="8">{!! old('what_we_do', $data->what_we_do ?? '') !!}</textarea>
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3 mt-4">Why Choose Us</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Why Choose Us</label>
                                            <textarea name="WhyChooseUs" id="whyChooseUs" class="form-control summernote" rows="6">{!! old('WhyChooseUs', $data->WhyChooseUs) !!}</textarea>
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3 mt-4">Our Commitment</h6>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Our Commitment</label>
                                            <textarea name="commitment" class="form-control" rows="4">{{ old('commitment', $data->commitment ?? '') }}</textarea>
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3 mt-4">CTA buttons (links)</h6>
                                        <p class="text-muted small">URLs for the three call-to-action buttons on the public About page. Leave blank to use default routes.</p>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Explore Our Services</label>
                                                <input type="text" name="cta_services_url" class="form-control" value="{{ old('cta_services_url', $data->cta_services_url ?? '') }}" placeholder="{{ url('/services') }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Book Your Stay or Adventure</label>
                                                <input type="text" name="cta_book_url" class="form-control" value="{{ old('cta_book_url', $data->cta_book_url ?? '') }}" placeholder="{{ route('contact') }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Contact Us</label>
                                                <input type="text" name="cta_contact_url" class="form-control" value="{{ old('cta_contact_url', $data->cta_contact_url ?? '') }}" placeholder="{{ route('contact') }}">
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary px-4"><i class="fa fa-save"></i> Update About Section</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="site-images-tab-pane" role="tabpanel">
                                    <form action="{{ route('saveSiteImages') }}" method="POST" enctype="multipart/form-data" class="p-4 bg-light rounded">
                                        @csrf

                                        <h6 class="border-bottom pb-2 mb-3">Site Images (Settings)</h6>
                                        <p class="text-muted small mb-4">These images are used across the site. Leave empty to keep current or use fallbacks.</p>

                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Home Header Image</label>
                                            @if($setting && $setting->home_header_image)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/images/site/' . $setting->home_header_image) }}" alt="Home Header" width="280" class="rounded shadow-sm">
                                                </div>
                                            @endif
                                            <input type="file" name="home_header_image" class="form-control" accept="image/*">
                                            <small class="text-muted">Used for the About page header. Leave empty to use About header image.</small>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Home Background Image</label>
                                            @if($setting && $setting->home_background_image)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/images/site/' . $setting->home_background_image) }}" alt="Home Background" width="280" class="rounded shadow-sm">
                                                </div>
                                            @endif
                                            <input type="file" name="home_background_image" class="form-control" accept="image/*">
                                            <small class="text-muted">Used for the homepage hero background. Replaces the slide/about image.</small>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Why Clients Trust — background</label>
                                            @if($setting && $setting->why_trust_background_image)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/images/site/' . $setting->why_trust_background_image) }}" alt="Why trust background" width="280" class="rounded shadow-sm">
                                                </div>
                                            @endif
                                            <input type="file" name="why_trust_background_image" class="form-control" accept="image/*">
                                            <small class="text-muted">Full-width parallax background for the “Why Clients Trust” section above the footer.</small>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Contact Us Middle Image</label>
                                            @if($setting && $setting->contact_us_middle_image)
                                                <div class="mb-2">
                                                    <img src="{{ asset('storage/images/site/' . $setting->contact_us_middle_image) }}" alt="Contact Middle" width="280" class="rounded shadow-sm">
                                                </div>
                                            @endif
                                            <input type="file" name="contact_us_middle_image" class="form-control" accept="image/*">
                                            <small class="text-muted">Background image for the contact form section on the Contact page.</small>
                                        </div>

                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary px-4"><i class="fa fa-save"></i> Save Site Images</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="terms-tab-pane" role="tabpanel">
                                    <div class="p-4 bg-light rounded">
                                        <p class="text-muted small">Edit the full terms document in one place (same content as the public Terms page).</p>
                                        <form action="{{ route('saveTerms', $terms->id) }}" method="POST">
                                            @csrf
                                            <label for="aboutTermsContent" class="form-label fw-semibold">Terms &amp; conditions</label>
                                            <textarea id="aboutTermsContent" name="terms" rows="14" class="form-control summernote">{!! old('terms', $terms->terms) !!}</textarea>
                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save</button>
                                                <a href="{{ route('getTerms') }}" class="btn btn-outline-secondary ms-2">Open full Terms editor</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
@endsection
