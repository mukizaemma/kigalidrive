/**
 * Car booking wizard: package → schedule → contact → send (WhatsApp / email).
 */
(function () {
    function formatDisplayDate(isoDate) {
        if (!isoDate) return '';
        const parts = isoDate.split('-');
        if (parts.length !== 3) return isoDate;
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return parseInt(parts[2], 10) + ' ' + months[parseInt(parts[1], 10) - 1] + ' ' + parts[0];
    }

    function formatTime12(timeVal) {
        if (!timeVal) return '';
        const parts = timeVal.split(':');
        if (parts.length < 2) return timeVal;
        let h = parseInt(parts[0], 10);
        const m = parts[1];
        const ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        return h + ':' + m + ' ' + ampm;
    }

    function formatRentalPeriod(pickup, dropoff) {
        const start = new Date(pickup + 'T12:00:00');
        const end = new Date(dropoff + 'T12:00:00');
        let days = Math.round((end - start) / 86400000);
        if (days < 1) days = 1;
        const dayLabel = days === 1 ? '1 day' : days + ' days';
        return dayLabel + ' (' + formatDisplayDate(pickup) + ' – ' + formatDisplayDate(dropoff) + ')';
    }

    function initForm(form) {
        if (form.dataset.kdrCarBookingInit === '1') {
            return;
        }
        form.dataset.kdrCarBookingInit = '1';

        const bookingTypeInput = form.querySelector('#booking_type');
        const panels = form.querySelectorAll('[data-panel]');
        const typeTabs = form.querySelectorAll('[data-booking-type]');
        const rentPanel = form.querySelector('[data-panel="rent"]');
        const stepPanes = rentPanel ? rentPanel.querySelectorAll('.kdr-car-booking__step-pane') : [];
        const stepIndicators = rentPanel ? rentPanel.querySelectorAll('[data-step-indicator]') : [];
        const btnPrev = rentPanel ? rentPanel.querySelector('.kdr-car-booking__prev') : null;
        const btnNext = rentPanel ? rentPanel.querySelector('.kdr-car-booking__next') : null;
        const channelsFooter = form.querySelector('[data-channels-footer]');
        const submitBtn = form.querySelector('.kdr-car-booking__submit');
        const summaryList = form.querySelector('.kdr-car-booking__summary-list');
        const durationInput = form.querySelector('#rental_duration');
        const driverInput = form.querySelector('#with_driver');
        const pickupDate = form.querySelector('#pickup_date');
        const dropoffDate = form.querySelector('#dropoff_date');
        const periodSummary = form.querySelector('#rental_period_summary');
        let currentStep = 1;
        const maxStep = 4;

        function activeType() {
            return bookingTypeInput ? bookingTypeInput.value : 'rent';
        }

        function setPanelFieldsDisabled() {
            const type = activeType();
            panels.forEach(function (panel) {
                const isActive = panel.getAttribute('data-panel') === type;
                panel.querySelectorAll('input, select, textarea').forEach(function (el) {
                    if (el.type === 'hidden') return;
                    el.disabled = !isActive;
                });
            });
        }

        function showPanel(type) {
            panels.forEach(function (panel) {
                panel.hidden = panel.getAttribute('data-panel') !== type;
            });
            if (bookingTypeInput) {
                bookingTypeInput.value = type;
            }
            typeTabs.forEach(function (tab) {
                const active = tab.getAttribute('data-booking-type') === type;
                tab.classList.toggle('active', active);
                tab.setAttribute('aria-selected', active ? 'true' : 'false');
            });
            setPanelFieldsDisabled();
            updateChannelsVisibility();
            updateNavButtons();
        }

        function updateChannelsVisibility() {
            if (!channelsFooter) return;
            const type = activeType();
            const show = type !== 'rent' || currentStep >= maxStep;
            channelsFooter.hidden = !show;
        }

        function goToStep(step) {
            currentStep = step;
            stepPanes.forEach(function (pane) {
                const n = parseInt(pane.getAttribute('data-step'), 10);
                const active = n === step;
                pane.classList.toggle('is-active', active);
                pane.hidden = !active;
            });
            stepIndicators.forEach(function (ind) {
                const n = parseInt(ind.getAttribute('data-step-indicator'), 10);
                ind.classList.toggle('is-active', n === step);
                ind.classList.toggle('is-done', n < step);
            });
            if (btnPrev) btnPrev.hidden = step <= 1;
            if (btnNext) {
                btnNext.textContent = step >= maxStep ? 'Review complete' : 'Continue';
                btnNext.hidden = step >= maxStep;
            }
            if (submitBtn) submitBtn.hidden = step < maxStep && activeType() === 'rent';
            updateChannelsVisibility();
            if (step === maxStep) {
                renderSummary();
            }
        }

        function selectedPackage() {
            const checked = rentPanel ? rentPanel.querySelector('input[name="rental_package"]:checked') : null;
            if (!checked) return null;
            return {
                label: checked.getAttribute('data-label') || checked.value,
                price: checked.getAttribute('data-price') || '',
                duration: checked.getAttribute('data-duration') || '',
                withDriver: checked.getAttribute('data-with-driver') || '0',
            };
        }

        function syncPackageHidden() {
            const pkg = selectedPackage();
            if (!pkg) return;
            if (durationInput) durationInput.value = pkg.duration;
            if (driverInput) driverInput.value = pkg.withDriver;
        }

        function updatePeriodSummary() {
            if (!periodSummary || !pickupDate || !dropoffDate) return;
            if (!pickupDate.value || !dropoffDate.value) {
                periodSummary.textContent = 'Rental length is calculated from your pickup and return dates.';
                return;
            }
            let text = formatRentalPeriod(pickupDate.value, dropoffDate.value);
            const pickupTime = form.querySelector('#pickup_time');
            const dropoffTime = form.querySelector('#dropoff_time');
            if (pickupTime && pickupTime.value) {
                text += ' · Pickup at ' + formatTime12(pickupTime.value);
            }
            if (dropoffTime && dropoffTime.value) {
                text += ' · Return at ' + formatTime12(dropoffTime.value);
            }
            periodSummary.innerHTML = '<strong>Estimated period:</strong> ' + text;
        }

        function syncDropoffMin() {
            if (!pickupDate || !dropoffDate) return;
            if (pickupDate.value) {
                dropoffDate.min = pickupDate.value;
                if (dropoffDate.value && dropoffDate.value < pickupDate.value) {
                    dropoffDate.value = pickupDate.value;
                }
            }
            updatePeriodSummary();
        }

        function renderSummary() {
            if (!summaryList) return;
            const pkg = selectedPackage();
            const rows = [];
            if (pkg) {
                rows.push(['Package', pkg.label + (pkg.price ? ' (' + pkg.price + ')' : '')]);
            }
            if (pickupDate && pickupDate.value) {
                let pickup = formatDisplayDate(pickupDate.value);
                const pt = form.querySelector('#pickup_time');
                if (pt && pt.value) pickup += ' at ' + formatTime12(pt.value);
                rows.push(['Pickup', pickup]);
            }
            if (dropoffDate && dropoffDate.value) {
                let ret = formatDisplayDate(dropoffDate.value);
                const dt = form.querySelector('#dropoff_time');
                if (dt && dt.value) ret += ' at ' + formatTime12(dt.value);
                rows.push(['Return', ret]);
            }
            const pl = form.querySelector('[name="pickup_location"]');
            const dl = form.querySelector('[name="dropoff_location"]');
            if (pl && pl.value) rows.push(['Pickup location', pl.value]);
            if (dl && dl.value) rows.push(['Return location', dl.value]);
            const name = form.querySelector('[data-panel="rent"] [name="name"]');
            const phone = form.querySelector('[data-panel="rent"] [name="phone"]');
            const email = form.querySelector('[data-panel="rent"] [name="email"]');
            if (name && name.value) rows.push(['Name', name.value]);
            if (phone && phone.value) rows.push(['Phone', phone.value]);
            if (email && email.value) rows.push(['Email', email.value]);

            summaryList.innerHTML = rows.map(function (row) {
                return '<div class="kdr-car-booking__summary-row"><dt>' + row[0] + '</dt><dd>' + row[1] + '</dd></div>';
            }).join('');
        }

        function validateStep(step) {
            const pane = rentPanel ? rentPanel.querySelector('.kdr-car-booking__step-pane[data-step="' + step + '"]') : null;
            if (!pane) return true;
            const fields = pane.querySelectorAll('input, select, textarea');
            for (let i = 0; i < fields.length; i++) {
                const field = fields[i];
                if (field.disabled || field.type === 'hidden') continue;
                if (!field.checkValidity()) {
                    field.reportValidity();
                    return false;
                }
            }
            if (step === 1) {
                syncPackageHidden();
            }
            return true;
        }

        function updateNavButtons() {
            if (activeType() !== 'rent' || !rentPanel || rentPanel.hidden) {
                if (btnPrev) btnPrev.hidden = true;
                if (btnNext) btnNext.hidden = true;
                if (submitBtn) submitBtn.hidden = false;
                return;
            }
            goToStep(currentStep);
        }

        typeTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                showPanel(tab.getAttribute('data-booking-type'));
                if (tab.getAttribute('data-booking-type') === 'rent') {
                    currentStep = 1;
                    goToStep(1);
                }
            });
        });

        if (btnNext) {
            btnNext.addEventListener('click', function () {
                if (!validateStep(currentStep)) return;
                if (currentStep < maxStep) {
                    goToStep(currentStep + 1);
                }
            });
        }

        if (btnPrev) {
            btnPrev.addEventListener('click', function () {
                if (currentStep > 1) {
                    goToStep(currentStep - 1);
                }
            });
        }

        form.querySelectorAll('input[name="rental_package"]').forEach(function (radio) {
            radio.addEventListener('change', syncPackageHidden);
        });

        if (pickupDate) pickupDate.addEventListener('change', syncDropoffMin);
        if (dropoffDate) dropoffDate.addEventListener('change', updatePeriodSummary);
        form.querySelectorAll('#pickup_time, #dropoff_time').forEach(function (el) {
            el.addEventListener('change', updatePeriodSummary);
        });

        const modal = document.getElementById('carBookingModal');
        if (modal) {
            modal.addEventListener('shown.bs.modal', function () {
                showPanel(activeType());
                if (activeType() === 'rent') {
                    goToStep(1);
                    syncDropoffMin();
                }
            });
        }

        showPanel(activeType());
        if (activeType() === 'rent' && rentPanel && !rentPanel.hidden) {
            goToStep(1);
            syncPackageHidden();
            syncDropoffMin();
        }
    }

    function initAll() {
        document.querySelectorAll('form.kdr-car-booking').forEach(initForm);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    document.addEventListener('shown.bs.modal', function (event) {
        const modal = event.target;
        if (!modal) return;
        modal.querySelectorAll('form.kdr-car-booking').forEach(initForm);
    });
})();
