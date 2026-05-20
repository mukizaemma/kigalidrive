@extends('layouts.adminBase')


@section('content')


        <!-- Sidebar Start -->
@include('admin.includes.sidebar')
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            @include('admin.includes.navbar')
            <!-- Navbar End -->

            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Our Partner Hotels</h6>

                        <div class="col-dm3">

                            <button type="button" class="btn btn-primary float-left" data-bs-toggle="modal" data-bs-target="#NewDestination">
                                <i class="fa fa-plus"></i> Add New Hotel
                            </button>
                            <a href="{{ route('admin.units.index') }}" class="btn btn-secondary float-left me-2">
                                <i class="fa fa-route"></i> Hotel Rooms
                            </a>

                            <a href="{{ route('getDestinations') }}" class="btn btn-secondary float-left me-2">
                                <i class="fa fa-route"></i> Hotel Destinations
                            </a>

                        </div>

                        <a href="{{ route('getRooms') }}" class="btn btn-primary btn-sm">Hotel Rooms</a>

                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#slideImage">
                            Add New Hotel
                        </button>
                    </div>

                    <!-- Search Field -->
                    <form method="GET" action="{{ route('getHotels') }}" class="mb-3">
                        <div class="input-group">

                            <input type="text" name="search"
                                placeholder="Search hotels by name, phone, location..."
                                value="{{ request('search') }}"
                                class="form-control">

                            @if(request('search'))
                                <a href="{{ route('getHotels') }}" 
                                class="btn btn-outline-secondary">
                                    <i class="fa fa-times"></i>
                                </a>
                            @endif

                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>


                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Hotel Name</th>
                                    <th>Phone</th>
                                    <th>Description</th>
                                    <th>Cover</th>
                                    <th>Status</th>
                                    <th width="140px">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($hotels as $rs)
                                <tr>
                                    <td>
                                        <a href="{{ route('editHotel', $rs->id) }}" class="fw-bold text-primary">
                                            {{ $rs->name }}
                                        </a>
                                        <div class="small text-muted">{{ $rs->type }} ->{{ $rs->location }}</div>
                                    </td>

                                    <td>{{ $rs->phone ?? '—' }}</td>

                                    <td>{{ \Illuminate\Support\Str::limit(strip_tags($rs->description), 80) }}</td>

                                    <td>
                                        <a href="{{ route('editHotel', $rs->id) }}">
                                            <img src="{{ asset('storage/images/hotels/' . $rs->image) }}"
                                                alt="Hotel Image"
                                                width="80px"
                                                class="rounded shadow-sm">
                                        </a>
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $rs->status == 'Active' ? 'success' : 'danger' }}">
                                            {{ $rs->status }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2">
                                                <a href="{{ route('editHotel',['id'=>$rs->id]) }}" class="btn btn-info"><i class="fa fa-images"></i></a>

                                            <a href="{{ route('destroyHotel', $rs->id) }}"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this hotel?')">
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No hotels found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $hotels->links() }}
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <!-- Content End -->

        <!-- The Modal -->
        <div class="modal fade" id="slideImage">
            <div class="modal-dialog modal-lg">
            <form class="form" action="{{ route('saveHotel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Adding New Hotel</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-body">

                            <!-- Partner Logo -->
                            <div class="row mb-4">
                                <div class="col-lg-4 col-sm-12">
                                    <label>Service</label>
                                    <select class="form-control" name="program_id" required>
                                        <option value="" disabled selected>Select Service</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->title }}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-lg-6 col-sm-12">
                                    <label>Hotel Cover Image</label>
                                    <label id="projectinput7" class="file center-block">
                                        <input type="file" id="image" name="image" required>
                                        <span class="file-custom"></span>
                                    </label>
                                </div>

                            </div>

                            <!-- Name & Website -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label>Property Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Business name" required>
                                </div>

                            </div>

                           <div class="row mb-3">

                            <div class="col-lg-4 col-sm-12">
                                <label>Partner Type</label>
                                <select class="form-control" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="hotel">Hotel</option>
                                    <option value="lodge">Lodge</option>
                                    <option value="guest_house">Guest House</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="motel">Motel</option>
                                    <option value="resort">Resort</option>
                                </select>
                            </div>

                            <!-- Stars / Rating -->
                            <div class="col-lg-4 col-sm-12">
                                <label>Stars</label>
                                <select class="form-control" name="stars" required>
                                    <option value="">Select Rating</option>
                                    <option value="0">Not Ranked</option>
                                    <option value="1">1 Star</option>
                                    <option value="2">2 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="5">5 Stars</option>
                                </select>
                            </div>

                        

                        </div>


                            <!-- Email & Phone -->
                            <div class="row mb-3">
                                <div class="col-lg-4 col-sm-12">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="example@email.com">
                                </div>

                                <div class="col-lg-4 col-sm-12">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" name="phone" placeholder="+250 7XX XXX XXX">
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <label>Hotel Website URL</label>
                                    <input type="text" class="form-control" name="website" placeholder="Eg: https://www.example.com">
                                </div>
                            </div>

                            <!-- Address & City -->
                            <div class="row mb-3">
                            <div class="col-lg-4 col-sm-12">
                                <label>Location (District)</label>
                                <select class="form-control" name="location" required>
                                    <option value="">Select District</option>

                                    <!-- Kigali -->
                                    <optgroup label="Kigali City">
                                        <option value="Gasabo">Gasabo</option>
                                        <option value="Kicukiro">Kicukiro</option>
                                        <option value="Nyarugenge">Nyarugenge</option>
                                    </optgroup>

                                    <!-- Northern Province -->
                                    <optgroup label="Northern Province">
                                        <option value="Burera">Burera</option>
                                        <option value="Gakenke">Gakenke</option>
                                        <option value="Gicumbi">Gicumbi</option>
                                        <option value="Musanze">Musanze</option>
                                        <option value="Rulindo">Rulindo</option>
                                    </optgroup>

                                    <!-- Southern Province -->
                                    <optgroup label="Southern Province">
                                        <option value="Gisagara">Gisagara</option>
                                        <option value="Huye">Huye</option>
                                        <option value="Kamonyi">Kamonyi</option>
                                        <option value="Muhanga">Muhanga</option>
                                        <option value="Nyamagabe">Nyamagabe</option>
                                        <option value="Nyanza">Nyanza</option>
                                        <option value="Nyaruguru">Nyaruguru</option>
                                        <option value="Ruhango">Ruhango</option>
                                    </optgroup>

                                    <!-- Eastern Province -->
                                    <optgroup label="Eastern Province">
                                        <option value="Bugesera">Bugesera</option>
                                        <option value="Gatsibo">Gatsibo</option>
                                        <option value="Kayonza">Kayonza</option>
                                        <option value="Kirehe">Kirehe</option>
                                        <option value="Ngoma">Ngoma</option>
                                        <option value="Nyagatare">Nyagatare</option>
                                        <option value="Rwamagana">Rwamagana</option>
                                    </optgroup>

                                    <!-- Western Province -->
                                    <optgroup label="Western Province">
                                        <option value="Karongi">Karongi</option>
                                        <option value="Ngororero">Ngororero</option>
                                        <option value="Nyabihu">Nyabihu</option>
                                        <option value="Nyamasheke">Nyamasheke</option>
                                        <option value="Rubavu">Rubavu</option>
                                        <option value="Rutsiro">Rutsiro</option>
                                        <option value="Rusizi">Rusizi</option>
                                    </optgroup>
                                </select>
                            </div>

                                <div class="col-lg-4 col-sm-12">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city" placeholder="Kigali, Musanze, Rubavu…">
                                </div>

                                <div class="col-lg-4 col-sm-12">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="address" placeholder="Street, Building, etc">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="form-label">Property Description</label>
                                    <textarea id="hotelDescription" rows="5" class="form-control" name="description"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary text-black">
                            <i class="fa fa-save"></i> Add New
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </form>

            </div>
        </div>

        <script>
            document.getElementById('hotelSearch').addEventListener('keyup', function () {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('#hotelTable tbody tr');

                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });

        </script>


        @include('admin.includes.footer')
@push('scripts')
<script>
$(function () {
    $('#slideImage').on('shown.bs.modal', function () {
        var $t = $('#hotelDescription');
        if (!$t.length || $t.next('.note-editor').length) return;
        $t.summernote({
            placeholder: 'Hotel Description',
            tabsize: 2,
            height: 220,
            toolbar: window._summernoteToolbar || []
        });
    });
});
</script>
@endpush
@endsection