@props([
    'payload',
    'yearUrls' => [],
    'canEditInventory' => false,
    'inventoryUpdateUrl' => null,
    'inventoryDetailUrl' => null,
])
@php
    $payload = $payload ?? null;
    $yearUrls = is_array($yearUrls ?? null) ? $yearUrls : [];
    $canEdit = $canEditInventory && !empty($inventoryUpdateUrl) && !empty($inventoryDetailUrl) && auth()->check();
@endphp
@if(empty($payload))
    <p class="text-muted mb-0">No calendar data.</p>
@elseif(empty($payload['months']))
    <p class="text-muted mb-0">
        @if(($payload['calendar_view'] ?? \App\Services\RoomBookingCalendarService::VIEW_UPCOMING) === \App\Services\RoomBookingCalendarService::VIEW_UPCOMING)
            Nothing to show in <strong>Upcoming</strong> for year {{ $payload['year'] ?? '' }}. Choose the current or a future year, or open <strong>Past history</strong> for earlier dates.
        @else
            Nothing to show for <strong>Past history</strong> with this year and view. Try another year.
        @endif
    </p>
@else
    @if(($payload['calendar_view'] ?? '') === \App\Services\RoomBookingCalendarService::VIEW_HISTORY)
        <p class="alert alert-secondary py-2 px-3 small mb-2 mb-md-3">You are viewing <strong>past months and days</strong> only. Switch to <strong>Upcoming</strong> to plan from today forward.</p>
    @endif
    <p class="text-muted small mb-2">
        Each number is <strong>vacant rooms</strong> guests may still book <strong>on Kigali Drive Rentals</strong> for that night.
        Kigali Drive Rentals only knows its own bookings; if the same rooms are sold on Booking.com, Expedia, or elsewhere, reduce the number here so it matches reality (or set <strong>0</strong> when fully booked off-platform).
        @if($canEdit)
            <span class="d-block mt-1">Click a day for that room type to open details, see Kigali Drive Rentals guests for that night, and set vacant rooms (this date only).</span>
        @endif
    </p>
    <div class="booking-cal-wrap" style="overflow-x: auto;">
        @if(count($yearUrls) > 0)
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="fw-bold text-secondary small">YEAR</span>
                @foreach($yearUrls as $y => $url)
                    <a href="{{ $url }}"
                       class="btn btn-sm {{ ($y == $payload['year']) ? 'btn-primary' : 'btn-outline-secondary' }}"
                       style="border-radius: 999px;">{{ $y }}</a>
                @endforeach
            </div>
        @endif

        @foreach($payload['months'] as $monthNum => $month)
            @php
                $visibleDays = $month['visible_days'] ?? range(1, (int) ($month['days_in_month'] ?? 31));
            @endphp
            <div class="mb-5">
                <h6 class="fw-bold mb-2" style="letter-spacing: .02em;">{{ strtoupper($month['name']) }} {{ $payload['year'] }}</h6>
                <table class="table table-bordered table-sm mb-0 booking-cal-table" style="font-size: 12px; min-width: 720px;">
                    <thead>
                        <tr>
                            <th class="text-start text-nowrap bg-light" style="min-width: 200px;">
                                Room / night<br>
                                <span class="fw-normal" style="font-size: 11px;">Vacant on Kigali Drive Rentals</span>
                            </th>
                            @foreach($visibleDays as $d)
                                <th class="text-center p-1">{{ $d }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($month['rooms'] as $row)
                            @php
                                $bookableClass = ($row['inventory_kind'] ?? '') === 'unit'
                                    ? \App\Models\Unit::class
                                    : \App\Models\HotelRoom::class;
                            @endphp
                            <tr>
                                <td class="text-nowrap fw-semibold bg-light">{{ $row['label'] ?? 'Room' }}</td>
                                @foreach($visibleDays as $d)
                                    @php
                                        $booked = (int) ($row['booked'][$d] ?? $row['days'][$d] ?? 0);
                                        $rem = isset($row['remaining'][$d]) ? (int) $row['remaining'][$d] : max(0, (int)($row['total_rooms'] ?? 1) - $booked);
                                        $ymd = sprintf('%04d-%02d-%02d', $payload['year'], $monthNum, $d);
                                        $tdClass = 'text-center p-1 ';
                                        if ($rem <= 0) {
                                            $tdClass .= 'table-danger';
                                        } elseif ($rem <= 2) {
                                            $tdClass .= 'table-warning';
                                        }
                                    @endphp
                                    <td class="{{ $tdClass }} {{ $canEdit ? 'inv-cell-edit' : '' }}"
                                        title="Kigali Drive Rentals bookings this night: {{ $booked }} · Vacant on Kigali Drive Rentals: {{ $rem }}"
                                        @if($canEdit)
                                            data-bookable-type="{{ $bookableClass }}"
                                            data-bookable-id="{{ (int) ($row['inventory_id'] ?? $row['id']) }}"
                                            data-date="{{ $ymd }}"
                                            data-max="{{ (int) ($row['total_rooms'] ?? 1) }}"
                                            data-booked="{{ $booked }}"
                                            data-remaining="{{ $rem }}"
                                            style="cursor: pointer;"
                                            role="button"
                                            tabindex="0"
                                        @endif
                                    ><span class="inv-rem-txt">{{ $rem }}</span></td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="table-secondary">
                            <td class="fw-bold">Occupancy %</td>
                            @foreach($visibleDays as $d)
                                <td class="text-center p-1 small fw-semibold">{{ number_format($month['occupancy'][$d] ?? 0, 2) }}%</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
    @if($canEdit)
        <div class="modal fade" id="Kigali Drive RentalsInvDayModal" tabindex="-1" aria-labelledby="Kigali Drive RentalsInvDayModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-0" id="Kigali Drive RentalsInvDayModalLabel">Day inventory</h5>
                            <div class="text-muted small mt-1" id="invModalDateLine"></div>
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="border:0;background:transparent;font-size:1.5rem;line-height:1;opacity:.5;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="invModalLoading" class="text-center py-5 text-muted">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span> Loading…
                        </div>
                        <div id="invModalError" class="alert alert-danger d-none" role="alert"></div>
                        <div id="invModalInner" class="d-none">
                            <p class="fw-semibold mb-2" id="invModalRoomLabel"></p>
                            <p class="small text-muted mb-3">
                                Vacant counts and overrides apply to <strong>this calendar date only</strong> (that night). Other dates are unchanged.
                            </p>

                            <h6 class="small text-uppercase text-muted mb-1">Kigali Drive Rentals bookings including this night</h6>
                            <p class="small text-muted mb-2">Amount shown is the booking&rsquo;s total for the <strong>full stay</strong> (not split per night).</p>
                            <div id="invModalBookingsWrap" class="table-responsive mb-3" style="max-height: 240px;">
                                <table class="table table-sm table-striped table-bordered mb-0" style="font-size: 12px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ref</th>
                                            <th>Guest</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Check-in</th>
                                            <th>Check-out</th>
                                            <th>Nights</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invModalBookingsBody"></tbody>
                                </table>
                            </div>
                            <p id="invModalNoBookings" class="text-muted small mb-3 d-none">No Kigali Drive Rentals bookings overlap this night for this room type.</p>

                            <hr class="my-3">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label small mb-0">Physical capacity (room type)</label>
                                    <div class="form-control-plaintext small py-1" id="invModalCapacity">—</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small mb-0">Automatic vacant (Kigali Drive Rentals data only)</label>
                                    <div class="form-control-plaintext small py-1" id="invModalComputed">—</div>
                                </div>
                            </div>

                            <div class="form-check mt-3 mb-2">
                                <input class="form-check-input" type="checkbox" id="invModalAutomatic">
                                <label class="form-check-label small" for="invModalAutomatic">
                                    Use automatic vacant (follow Kigali Drive Rentals bookings only for this day)
                                </label>
                            </div>
                            <div class="mb-1">
                                <label class="form-label small mb-1" for="invModalVacantInput">Vacant rooms on Kigali Drive Rentals for this night</label>
                                <input type="number" class="form-control form-control-sm" id="invModalVacantInput" min="0" step="1" style="max-width: 140px;">
                                <div class="form-text small">Lower this if rooms are booked on other channels (Booking.com, Expedia, etc.). Use 0 for none available on Kigali Drive Rentals.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm" id="invModalSaveBtn" disabled>Save</button>
                    </div>
                </div>
            </div>
        </div>
        @once
            <script>
            (function () {
                const detailUrl = @json($inventoryDetailUrl);
                const updateUrl = @json($inventoryUpdateUrl);
                const token = @json(csrf_token());
                const modalEl = document.getElementById('Kigali Drive RentalsInvDayModal');
                const loadingEl = document.getElementById('invModalLoading');
                const innerEl = document.getElementById('invModalInner');
                const errEl = document.getElementById('invModalError');
                const saveBtn = document.getElementById('invModalSaveBtn');
                const bookingsBody = document.getElementById('invModalBookingsBody');
                const noBookingsEl = document.getElementById('invModalNoBookings');
                const autoChk = document.getElementById('invModalAutomatic');
                const vacantInp = document.getElementById('invModalVacantInput');

                let ctx = { bookable_type: '', bookable_id: 0, date: '', capacity: 0, computed_vacant: 0 };
                var invBsModal = null;

                function showModal() {
                    if (window.bootstrap && bootstrap.Modal) {
                        if (!invBsModal) {
                            invBsModal = typeof bootstrap.Modal.getOrCreateInstance === 'function'
                                ? bootstrap.Modal.getOrCreateInstance(modalEl)
                                : new bootstrap.Modal(modalEl);
                        }
                        invBsModal.show();
                    } else if (window.jQuery && jQuery.fn.modal) {
                        jQuery(modalEl).modal('show');
                    }
                }
                function hideModal() {
                    if (window.bootstrap && bootstrap.Modal) {
                        if (invBsModal) {
                            invBsModal.hide();
                        } else if (typeof bootstrap.Modal.getInstance === 'function') {
                            var i = bootstrap.Modal.getInstance(modalEl);
                            if (i) i.hide();
                        }
                    } else if (window.jQuery && jQuery.fn.modal) {
                        jQuery(modalEl).modal('hide');
                    }
                }

                function esc(s) {
                    if (s === null || s === undefined) return '';
                    const d = document.createElement('div');
                    d.textContent = String(s);
                    return d.innerHTML;
                }

                function resetModalUI() {
                    errEl.classList.add('d-none');
                    errEl.textContent = '';
                    loadingEl.classList.remove('d-none');
                    innerEl.classList.add('d-none');
                    saveBtn.disabled = true;
                    bookingsBody.innerHTML = '';
                }

                function setAutomaticToggle(data) {
                    const hasCap = data.has_manual_cap === true;
                    autoChk.checked = !hasCap;
                    vacantInp.disabled = !hasCap;
                    vacantInp.max = data.capacity;
                    vacantInp.min = 0;
                    vacantInp.value = String(data.effective_vacant !== undefined ? data.effective_vacant : data.computed_vacant);
                }

                autoChk.addEventListener('change', function () {
                    vacantInp.disabled = autoChk.checked;
                    if (autoChk.checked) {
                        vacantInp.value = String(ctx.computed_vacant);
                    }
                });

                document.body.addEventListener('click', function (ev) {
                    const td = ev.target.closest('.booking-cal-wrap td.inv-cell-edit');
                    if (!td) return;

                    const bookableType = td.getAttribute('data-bookable-type');
                    const bookableId = td.getAttribute('data-bookable-id');
                    const date = td.getAttribute('data-date');
                    if (!bookableType || !bookableId || !date) return;

                    ctx.bookable_type = bookableType;
                    ctx.bookable_id = parseInt(bookableId, 10);
                    ctx.date = date;

                    resetModalUI();
                    document.getElementById('Kigali Drive RentalsInvDayModalLabel').textContent = 'Vacant rooms for one night';
                    document.getElementById('invModalDateLine').textContent = '';
                    showModal();

                    const params = new URLSearchParams({
                        bookable_type: bookableType,
                        bookable_id: String(bookableId),
                        date: date
                    });
                    fetch(detailUrl + '?' + params.toString(), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin'
                    }).then(function (r) {
                        if (!r.ok) throw new Error('HTTP ' + r.status);
                        return r.json();
                    }).then(function (data) {
                        loadingEl.classList.add('d-none');
                        innerEl.classList.remove('d-none');
                        saveBtn.disabled = false;

                        ctx.capacity = data.capacity;
                        ctx.computed_vacant = data.computed_vacant;

                        document.getElementById('invModalDateLine').textContent = data.date_formatted || data.date;
                        document.getElementById('invModalRoomLabel').textContent = data.room_label || '';
                        document.getElementById('invModalCapacity').textContent = data.capacity;
                        document.getElementById('invModalComputed').textContent = data.computed_vacant;
                        setAutomaticToggle(data);

                        var wrapEl = document.getElementById('invModalBookingsWrap');
                        if (data.bookings && data.bookings.length) {
                            wrapEl.classList.remove('d-none');
                            noBookingsEl.classList.add('d-none');
                            bookingsBody.innerHTML = data.bookings.map(function (b) {
                                return '<tr><td class="text-nowrap small">' + esc(b.reference_number) + '</td>' +
                                    '<td class="text-nowrap">' + esc(b.guest_name) + '</td>' +
                                    '<td class="text-break small">' + esc(b.guest_email) + '</td>' +
                                    '<td class="text-nowrap small">' + esc(b.guest_phone) + '</td>' +
                                    '<td class="text-nowrap small">' + esc(b.check_in_formatted) + '</td>' +
                                    '<td class="text-nowrap small">' + esc(b.check_out_formatted) + '</td>' +
                                    '<td class="text-center">' + esc(b.nights) + '</td>' +
                                    '<td class="text-nowrap">' + esc(b.total_amount_formatted) + '</td></tr>';
                            }).join('');
                        } else {
                            wrapEl.classList.add('d-none');
                            noBookingsEl.classList.remove('d-none');
                        }
                    }).catch(function () {
                        loadingEl.classList.add('d-none');
                        errEl.textContent = 'Could not load this day. Please try again.';
                        errEl.classList.remove('d-none');
                    });
                });

                saveBtn.addEventListener('click', function () {
                    const body = {
                        bookable_type: ctx.bookable_type,
                        bookable_id: ctx.bookable_id,
                        date: ctx.date
                    };
                    if (autoChk.checked) {
                        body.automatic = true;
                    } else {
                        let n = parseInt(vacantInp.value, 10);
                        if (isNaN(n) || n < 0) {
                            window.alert('Enter a valid vacant count (0–' + ctx.capacity + ').');
                            return;
                        }
                        if (n > ctx.capacity) n = ctx.capacity;
                        body.target_remaining = n;
                    }
                    saveBtn.disabled = true;
                    fetch(updateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(body)
                    }).then(function (r) {
                        if (!r.ok) throw new Error('Save failed');
                        return r.json().catch(function () { return {}; });
                    }).then(function () {
                        hideModal();
                        window.location.reload();
                    }).catch(function () {
                        window.alert('Could not save. Please try again.');
                        saveBtn.disabled = false;
                    });
                });

                modalEl.addEventListener('hidden.bs.modal', function () {
                    saveBtn.disabled = true;
                });
            })();
            </script>
        @endonce
    @endif
@endif
