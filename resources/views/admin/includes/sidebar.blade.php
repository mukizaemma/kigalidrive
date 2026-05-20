<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="{{ asset('storage/images') . (optional($setting)->logo ?? '') }}" alt="" style="width: 40px; height: 40px;">
            </div>
            <div class="ms-3">
                <h6 class="mb-0">{{ optional($setting)->company ?? 'Kigali Drive Rentals' }}</h6>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="{{ route('dashboard') }}" class="nav-item nav-link"><i class="fas fa-grip-horizontal me-2"></i>Dashboard</a>
            <hr class="my-2">
            <small class="text-muted px-3 text-uppercase">Listings</small>
            <a href="{{ route('getCars') }}" class="nav-item nav-link"><i class="fas fa-car me-2"></i>Cars</a>
            <a href="{{ route('admin.properties.index') }}" class="nav-item nav-link"><i class="fas fa-building me-2"></i>Apartments</a>
            <a href="{{ route('admin.services.index') }}" class="nav-item nav-link"><i class="fas fa-concierge-bell me-2"></i>Services</a>
            <a href="{{ route('admin.units.index') }}" class="nav-item nav-link"><i class="fas fa-door-open me-2"></i>Units</a>
            <a href="{{ route('admin.listing-requests.index') }}" class="nav-item nav-link"><i class="fas fa-inbox me-2"></i>Listing requests</a>
            <a href="{{ route('admin.enquiries.index') }}" class="nav-item nav-link"><i class="fas fa-envelope-open-text me-2"></i>Enquiries</a>
            <hr class="my-2">
            <small class="text-muted px-3 text-uppercase">Bookings</small>
            <a href="{{ route('admin.bookings.index') }}" class="nav-item nav-link"><i class="fas fa-calendar-check me-2"></i>Reservations</a>
            <a href="{{ route('admin.carBookings.index') }}" class="nav-item nav-link"><i class="fas fa-car-side me-2"></i>Car bookings</a>
            <a href="{{ route('admin.booking-calendar.index') }}" class="nav-item nav-link"><i class="fas fa-border-all me-2"></i>Calendar</a>
            <hr class="my-2">
            <small class="text-muted px-3 text-uppercase">Content</small>
            <a href="{{ route('getBlogs') }}" class="nav-item nav-link"><i class="fas fa-newspaper me-2"></i>Updates / Blog</a>
            <a href="{{ route('blogsComment') }}" class="nav-item nav-link"><i class="fas fa-comments me-2"></i>Blog comments</a>
            <a href="{{ route('slides') }}" class="nav-item nav-link"><i class="fas fa-images me-2"></i>Home slides</a>
            <a href="{{ route('aboutPage') }}" class="nav-item nav-link"><i class="fas fa-info-circle me-2"></i>About page</a>
            <a href="{{ route('admin.rental-agreement.edit') }}" class="nav-item nav-link"><i class="fas fa-file-contract me-2"></i>Rental agreement</a>
            <hr class="my-2">
            <small class="text-muted px-3 text-uppercase">Setup</small>
            <a href="{{ route('amenities.index') }}" class="nav-item nav-link"><i class="fas fa-list me-2"></i>Amenities</a>
            <a href="{{ route('admin.facility-categories.index') }}" class="nav-item nav-link"><i class="fas fa-folder me-2"></i>Amenity categories</a>
            <a href="{{ route('getTerms') }}" class="nav-item nav-link"><i class="fas fa-file-alt me-2"></i>Terms</a>
            <a href="{{ route('setting') }}" class="nav-item nav-link"><i class="fas fa-cog me-2"></i>Settings</a>
            <a href="{{ route('my.profile') }}" class="nav-item nav-link"><i class="fas fa-user-circle me-2"></i>My profile</a>
            @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('users') }}" class="nav-item nav-link"><i class="fa fa-users me-2"></i>Users</a>
            @endif
            <hr class="my-2">
            <a href="{{ route('home') }}" class="nav-item nav-link" target="_blank"><i class="fas fa-external-link-alt me-2"></i>View website</a>
        </div>
    </nav>
</div>
