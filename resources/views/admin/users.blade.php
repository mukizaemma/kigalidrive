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

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">
                            @if(isset($filter) && $filter == 'admins')
                                System Administrators
                            @elseif(isset($filter) && $filter == 'users')
                                Regular Users
                            @else
                                @if(isset($isSuperAdmin) && $isSuperAdmin)
                                    All Users
                                @else
                                    Regular Users
                                @endif
                            @endif
                        </h6>
                        <div class="d-flex gap-2 align-items-center">
                            @if(isset($isSuperAdmin) && $isSuperAdmin)
                                {{-- Only super admin can see admins --}}
                                <a href="{{ route('users', ['filter' => 'admins']) }}" 
                                   class="btn {{ (isset($filter) && $filter == 'admins') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    <i class="fas fa-user-shield me-1"></i>View Admins
                                </a>
                                <a href="{{ route('users', ['filter' => 'users']) }}" 
                                   class="btn {{ (isset($filter) && $filter == 'users') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    <i class="fas fa-users me-1"></i>View Users
                                </a>
                                <a href="{{ route('users') }}" 
                                   class="btn {{ (!isset($filter) || $filter == 'all') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    <i class="fas fa-list me-1"></i>View All
                                </a>
                            @else
                                {{-- Regular admins can only see users filter --}}
                                <a href="{{ route('users', ['filter' => 'users']) }}" 
                                   class="btn {{ (isset($filter) && $filter == 'users') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    <i class="fas fa-users me-1"></i>View Users
                                </a>
                                <a href="{{ route('users') }}" 
                                   class="btn {{ (!isset($filter) || $filter == 'all') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    <i class="fas fa-list me-1"></i>View All
                                </a>
                            @endif
                        </div>
                    </div>

                    @if(isset($isSuperAdmin) && $isSuperAdmin && isset($segmentCounts))
                        @php
                            $segQuery = array_filter(['filter' => $filter ?? 'all', 'search' => $search ?? null]);
                        @endphp
                        <div class="d-flex flex-wrap gap-2 align-items-center mb-3 pb-3 border-bottom">
                            <span class="text-muted small me-1">Cleanup segments:</span>
                            <a href="{{ route('users', $segQuery) }}"
                               class="btn btn-sm {{ empty($segment ?? null) ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                All segments
                            </a>
                            <a href="{{ route('users', array_merge($segQuery, ['segment' => 'unverified'])) }}"
                               class="btn btn-sm {{ ($segment ?? '') === 'unverified' ? 'btn-warning' : 'btn-outline-warning' }}"
                               title="Email not verified">
                                Not verified <span class="badge bg-light text-dark ms-1">{{ $segmentCounts['unverified'] }}</span>
                            </a>
                            <a href="{{ route('users', array_merge($segQuery, ['segment' => 'verified_no_property'])) }}"
                               class="btn btn-sm {{ ($segment ?? '') === 'verified_no_property' ? 'btn-info' : 'btn-outline-info' }}"
                               title="Verified email but no property or legacy hotel listing">
                                Verified, no listing <span class="badge bg-light text-dark ms-1">{{ $segmentCounts['verified_no_property'] }}</span>
                            </a>
                            <a href="{{ route('users', array_merge($segQuery, ['segment' => 'with_properties'])) }}"
                               class="btn btn-sm {{ ($segment ?? '') === 'with_properties' ? 'btn-success' : 'btn-outline-success' }}"
                               title="Has at least one property or legacy hotel">
                                With listings <span class="badge bg-light text-dark ms-1">{{ $segmentCounts['with_properties'] }}</span>
                            </a>
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Search Box -->
                    <div class="mb-4">
                        <form method="GET" action="{{ route('users') }}" id="searchForm">
                            <input type="hidden" name="filter" value="{{ $filter ?? 'all' }}" id="filterInput">
                            @if(!empty($segment))
                                <input type="hidden" name="segment" value="{{ $segment }}" id="segmentInput">
                            @endif
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="userSearch" 
                                       name="search" 
                                       placeholder="Search by name or email..." 
                                       value="{{ $search ?? '' }}"
                                       autocomplete="off">
                                @if(isset($search) && $search)
                                    <a href="{{ route('users', array_filter(['filter' => $filter ?? 'all', 'segment' => $segment ?? null])) }}" class="btn btn-outline-secondary" title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    @if(isset($search) && $search)
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Found {{ $users->count() }} result(s) for "<strong>{{ $search }}</strong>"
                        </div>
                    @endif

                    @if(!empty($canBulkDeleteSelected))
                        <form method="POST" action="{{ route('admin.users.bulk-delete') }}" id="bulkDeleteForm" class="mb-2">
                            @csrf
                            <button type="button" class="btn btn-danger btn-sm mb-2" id="bulkDeleteBtn" disabled>
                                <i class="fas fa-trash-alt me-1"></i>Delete selected
                            </button>
                        </form>
                    @endif

                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    @if(!empty($canBulkDeleteSelected))
                                        <th scope="col" style="width: 42px;">
                                            <input type="checkbox" class="form-check-input" id="selectAllUsers" title="Select all deletable users">
                                        </th>
                                    @endif
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Email Verified</th>
                                    <th scope="col">Properties</th>
                                    <th scope="col">Bookings</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                @php
                                    $roleVal = $user->role;
                                    if ($roleVal == 1 || $roleVal === '1') {
                                        $roleLabel = 'Super Admin';
                                        $roleClass = 'bg-danger';
                                    } elseif ($roleVal == 2 || $roleVal === '2') {
                                        $roleLabel = 'Admin';
                                        $roleClass = 'bg-primary';
                                    } else {
                                        $roleLabel = 'User';
                                        $roleClass = 'bg-secondary';
                                    }
                                    $listingTotal = (int) ($user->properties_count ?? 0) + (int) ($user->owned_hotels_count ?? 0);
                                    $guestBookings = (int) ($user->guest_bookings_count ?? 0);
                                    $hostBookings = (int) ($user->host_bookings_count ?? 0);
                                    $canBulkDeleteRow = !empty($canBulkDeleteSelected) && (int)($user->role ?? 0) !== 1;
                                @endphp
                                <tr>
                                    @if(!empty($canBulkDeleteSelected))
                                        <td>
                                            @if($canBulkDeleteRow)
                                                <input type="checkbox" class="form-check-input user-delete-cb" form="bulkDeleteForm" name="ids[]" value="{{ $user->id }}"
                                                       data-user-name="{{ $user->name }}" data-user-email="{{ $user->email }}">
                                            @endif
                                        </td>
                                    @endif
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $roleClass }}">{{ $roleLabel }}</span>
                                    </td>
                                    <td>
                                        @if($user->hasVerifiedEmail())
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-circle me-1"></i>Not Verified
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-start">
                                        <span class="badge bg-info">{{ $listingTotal }}</span>
                                        @if((int) ($user->owned_hotels_count ?? 0) > 0)
                                            <br><small class="text-muted">Includes {{ (int) $user->owned_hotels_count }} legacy hotel{{ (int) $user->owned_hotels_count === 1 ? '' : 's' }}</small>
                                        @endif
                                    </td>
                                    <td class="text-start">
                                        <div class="d-flex flex-column gap-1 align-items-start">
                                            <span class="badge bg-primary" title="Bookings this user made as a guest">Guest {{ $guestBookings }}</span>
                                            <span class="badge bg-success" title="Bookings at listings they own (properties, units, or legacy hotels/rooms)">Host {{ $hostBookings }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="button-group d-flex gap-1 flex-wrap">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-primary btn-sm" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if(!$user->hasVerifiedEmail())
                                                @if($user->role != 1 || (isset($isSuperAdmin) && $isSuperAdmin))
                                                    <a href="{{ route('admin.users.verify', $user->id) }}" class="btn btn-success btn-sm" 
                                                       title="Verify Email" onclick="return confirm('Are you sure you want to verify this user\'s email?')">
                                                        <i class="fas fa-check-circle"></i>
                                                    </a>
                                                @endif
                                            @endif
                                            @if($user->role != 1 && isset($isSuperAdmin) && $isSuperAdmin)
                                                <a href="{{ route('makeAdmin', ['id' => $user->id]) }}" class="btn btn-info btn-sm" title="Make Super Admin"
                                                   onclick="return confirm('Grant this user Super Admin access? They will have full admin rights. Continue?');">
                                                    <i class="fa fa-user-shield"></i>
                                                </a>
                                            @endif
                                            @if(isset($isSuperAdmin) && $isSuperAdmin)
                                                <a href="{{ route('deleteUser', ['id' => $user->id]) }}" class="btn btn-danger btn-sm" 
                                                   title="Delete" onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ !empty($canBulkDeleteSelected) ? '8' : '7' }}" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                                            <p class="mb-0">No users found</p>
                                            @if(isset($search) && $search)
                                                <small>Try a different search term or <a href="{{ route('users', ['filter' => $filter ?? 'all']) }}">clear search</a></small>
                                            @elseif(isset($filter) && $filter == 'admins')
                                                <small>No administrators found</small>
                                            @else
                                                <small>No users in the system</small>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(!empty($canBulkDeleteSelected))
                        <div class="modal fade" id="bulkDeleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="bulkDeleteConfirmModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="bulkDeleteConfirmModalLabel">Confirm bulk deletion</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-danger font-weight-bold mb-2">These accounts will be permanently removed:</p>
                                        <ul class="list-group list-group-flush" id="bulkDeleteUserList"></ul>
                                        <p class="text-muted small mt-3 mb-0">This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger" id="bulkDeleteConfirmSubmit" form="bulkDeleteForm">
                                            Delete permanently
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Recent Sales End -->



            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Kigali Drive Rentals</a>, All Right Reserved. 
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            Designed By <a href="https://iremetech.com">Ireme Technologies</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        @include('admin.includes.footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearch');
    const searchForm = document.getElementById('searchForm');
    const filterInput = document.getElementById('filterInput');
    let searchTimeout;

    const selectAll = document.getElementById('selectAllUsers');
    const bulkBtn = document.getElementById('bulkDeleteBtn');
    const bulkDeleteModalEl = document.getElementById('bulkDeleteConfirmModal');
    const bulkDeleteUserList = document.getElementById('bulkDeleteUserList');

    function refreshBulkButton() {
        if (!bulkBtn) return;
        const any = document.querySelectorAll('.user-delete-cb:checked').length > 0;
        bulkBtn.disabled = !any;
    }

    function openBulkDeleteModal() {
        if (!bulkDeleteModalEl || !bulkDeleteUserList) return;
        const checked = document.querySelectorAll('.user-delete-cb:checked');
        if (!checked.length) return;

        bulkDeleteUserList.innerHTML = '';
        checked.forEach(function(cb) {
            const name = cb.getAttribute('data-user-name') || '—';
            const email = cb.getAttribute('data-user-email') || '—';
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-start flex-column flex-sm-row gap-1';
            li.innerHTML = '<span class="fw-medium">' + escapeHtml(name) + '</span><span class="text-muted small">' + escapeHtml(email) + '</span>';
            bulkDeleteUserList.appendChild(li);
        });

        if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
            window.jQuery('#bulkDeleteConfirmModal').modal('show');
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(bulkDeleteModalEl).show();
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    if (bulkBtn) {
        bulkBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openBulkDeleteModal();
        });
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.user-delete-cb').forEach(function(cb) {
                cb.checked = selectAll.checked;
            });
            refreshBulkButton();
        });
    }
    document.querySelectorAll('.user-delete-cb').forEach(function(cb) {
        cb.addEventListener('change', refreshBulkButton);
    });

    // Auto-search functionality with debounce
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            
            const searchValue = e.target.value.trim();
            
            // Clear timeout and wait 500ms after user stops typing
            searchTimeout = setTimeout(function() {
                if (searchValue.length >= 2 || searchValue.length === 0) {
                    // Only search if at least 2 characters or empty (to clear)
                    searchForm.submit();
                }
            }, 500);
        });

        // Allow Enter key to submit immediately
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                searchForm.submit();
            }
        });
    }

    // Update filter input when filter buttons are clicked
    document.querySelectorAll('a[href*="filter="]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            const url = new URL(this.href);
            const filter = url.searchParams.get('filter') || 'all';
            if (filterInput) {
                filterInput.value = filter;
            }
            const segIn = document.getElementById('segmentInput');
            const seg = url.searchParams.get('segment');
            if (segIn && seg) {
                segIn.value = seg;
            }
        });
    });
});
</script>
@endsection
