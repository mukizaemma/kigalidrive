{{-- Guests & Rooms selector: displays "X adults · Y children · Z rooms", opens dropdown with counters. --}}
@php
    $adults = (int) request('adults', 1);
    $children = (int) request('children', 0);
    $rooms = (int) request('rooms', 1);
    $guests = $adults + $children;
    $selectorId = $selectorId ?? 'guests-rooms-selector';
    $inputId = $selectorId . '-input';
    $dropdownId = $selectorId . '-dropdown';
@endphp
<div class="position-relative guests-rooms-wrapper" id="{{ $selectorId }}" style="overflow: visible;">
    <div class="position-relative">
        <i class="fas fa-users position-absolute" style="left: 14px; top: 50%; transform: translateY(-50%); color: #888; font-size: 14px;"></i>
        <input type="text" id="{{ $inputId }}" class="form-control form-control-sm guests-rooms-display" readonly placeholder="Adults, children, rooms" value="{{ $adults }} adults · {{ $children }} children · {{ $rooms }} room{{ $rooms !== 1 ? 's' : '' }}" style="padding-left: 38px; border-radius: 8px; height: 44px; cursor: pointer; background: #fff;">
        <i class="fas fa-chevron-down position-absolute" style="right: 14px; top: 50%; transform: translateY(-50%); color: #888; font-size: 12px;"></i>
    </div>
    <input type="hidden" name="adults" value="{{ $adults }}" class="guests-adults">
    <input type="hidden" name="children" value="{{ $children }}" class="guests-children">
    <input type="hidden" name="rooms" value="{{ $rooms }}" class="guests-rooms">
    <input type="hidden" name="guests" value="{{ $guests }}" class="guests-total">

    <div class="guests-rooms-dropdown shadow-lg rounded-3 p-4 bg-white" id="{{ $dropdownId }}" style="display: none; position: fixed; left: 0; top: 0; z-index: 9999; min-width: 280px; border: 2px solid #0d6efd; box-shadow: 0 10px 40px rgba(0,0,0,0.25);">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="fw-bold">Adults</span>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm rounded-circle p-0 guests-btn-minus" data-target="adults" style="width: 36px; height: 36px;">−</button>
                <span class="guests-value-adults fw-bold" style="min-width: 24px; text-align: center;">{{ $adults }}</span>
                <button type="button" class="btn btn-primary btn-sm rounded-circle p-0 guests-btn-plus" data-target="adults" style="width: 36px; height: 36px;">+</button>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="fw-bold">Children</span>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm rounded-circle p-0 guests-btn-minus" data-target="children" style="width: 36px; height: 36px;">−</button>
                <span class="guests-value-children fw-bold" style="min-width: 24px; text-align: center;">{{ $children }}</span>
                <button type="button" class="btn btn-primary btn-sm rounded-circle p-0 guests-btn-plus" data-target="children" style="width: 36px; height: 36px;">+</button>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="fw-bold">Rooms</span>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm rounded-circle p-0 guests-btn-minus" data-target="rooms" style="width: 36px; height: 36px;">−</button>
                <span class="guests-value-rooms fw-bold" style="min-width: 24px; text-align: center;">{{ $rooms }}</span>
                <button type="button" class="btn btn-primary btn-sm rounded-circle p-0 guests-btn-plus" data-target="rooms" style="width: 36px; height: 36px;">+</button>
            </div>
        </div>
        <div class="pt-2 border-top">
            <button type="button" class="btn btn-primary w-100 guests-rooms-done">Done</button>
        </div>
    </div>
</div>

<style>
.guests-rooms-wrapper .guests-btn-minus:disabled { opacity: 0.5; cursor: not-allowed; }
.guests-rooms-dropdown .btn-sm { font-size: 18px; line-height: 1; }
.guests-rooms-wrapper { overflow: visible !important; }
/* Prevent parent from clipping the dropdown */
form .guests-rooms-wrapper { overflow: visible !important; }
</style>

<script>
(function() {
    var wrap = document.getElementById('{{ $selectorId }}');
    if (!wrap) return;
    var input = document.getElementById('{{ $inputId }}');
    var dropdown = document.getElementById('{{ $dropdownId }}');
    var displayAdults = wrap.querySelector('.guests-value-adults');
    var displayChildren = wrap.querySelector('.guests-value-children');
    var displayRooms = wrap.querySelector('.guests-value-rooms');
    var hiddenAdults = wrap.querySelector('input[name="adults"]');
    var hiddenChildren = wrap.querySelector('input[name="children"]');
    var hiddenRooms = wrap.querySelector('input[name="rooms"]');
    var hiddenGuests = wrap.querySelector('input[name="guests"]');

    function updateDisplay() {
        var a = parseInt(hiddenAdults.value, 10) || 0;
        var c = parseInt(hiddenChildren.value, 10) || 0;
        var r = parseInt(hiddenRooms.value, 10) || 1;
        a = Math.max(1, a);
        r = Math.max(1, r);
        // Suggest minimum rooms when guests increase (e.g. 4 guests -> at least 2 rooms)
        var totalGuests = a + c;
        var suggestedRooms = Math.max(1, Math.ceil(totalGuests / 2));
        if (r < suggestedRooms) r = suggestedRooms;
        hiddenAdults.value = a;
        hiddenChildren.value = c;
        hiddenRooms.value = r;
        hiddenGuests.value = totalGuests;
        displayAdults.textContent = a;
        displayChildren.textContent = c;
        displayRooms.textContent = r;
        input.value = a + ' adults · ' + c + ' children · ' + r + ' room' + (r !== 1 ? 's' : '');
        wrap.querySelectorAll('.guests-btn-minus').forEach(function(btn) {
            var t = btn.getAttribute('data-target');
            if (t === 'adults') btn.disabled = a <= 1;
            else if (t === 'children') btn.disabled = c <= 0;
            else if (t === 'rooms') btn.disabled = r <= 1;
        });
    }

    function toggleDropdown() {
        var isOpen = dropdown.style.display === 'block';
        if (!isOpen) {
            var rect = input.getBoundingClientRect();
            dropdown.style.left = rect.left + 'px';
            dropdown.style.top = (rect.bottom + 4) + 'px';
            dropdown.style.minWidth = Math.max(280, rect.width) + 'px';
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }

    input.addEventListener('click', function(e) { e.preventDefault(); toggleDropdown(); });

    wrap.querySelectorAll('.guests-btn-plus').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var t = btn.getAttribute('data-target');
            if (t === 'adults') hiddenAdults.value = Math.min(20, parseInt(hiddenAdults.value, 10) + 1);
            else if (t === 'children') hiddenChildren.value = Math.min(19, parseInt(hiddenChildren.value, 10) + 1);
            else if (t === 'rooms') hiddenRooms.value = Math.min(20, parseInt(hiddenRooms.value, 10) + 1);
            updateDisplay();
        });
    });
    wrap.querySelectorAll('.guests-btn-minus').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var t = btn.getAttribute('data-target');
            if (t === 'adults' && parseInt(hiddenAdults.value, 10) > 1) hiddenAdults.value = parseInt(hiddenAdults.value, 10) - 1;
            else if (t === 'children' && parseInt(hiddenChildren.value, 10) > 0) hiddenChildren.value = parseInt(hiddenChildren.value, 10) - 1;
            else if (t === 'rooms' && parseInt(hiddenRooms.value, 10) > 1) hiddenRooms.value = parseInt(hiddenRooms.value, 10) - 1;
            updateDisplay();
        });
    });
    wrap.querySelector('.guests-rooms-done') && wrap.querySelector('.guests-rooms-done').addEventListener('click', function() { toggleDropdown(); });

    document.addEventListener('click', function(e) {
        if (!wrap.contains(e.target)) dropdown.style.display = 'none';
    });
    updateDisplay();
})();
</script>
