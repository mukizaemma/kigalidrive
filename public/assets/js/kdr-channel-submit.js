/**
 * Channel picker validation and external send (WhatsApp / email) in a new tab.
 */
(function () {
    const EXTERNAL_CHANNELS = ['whatsapp', 'email'];

    function clearChannelError(form) {
        const picker = form.querySelector('.kdr-channel-picker');
        const errEl = picker?.querySelector('.kdr-channel-picker__error');
        picker?.classList.remove('is-invalid');
        if (errEl) {
            errEl.textContent = '';
            errEl.classList.add('d-none');
        }
    }

    function showChannelError(form, message) {
        const picker = form.querySelector('.kdr-channel-picker');
        const errEl = picker?.querySelector('.kdr-channel-picker__error');
        picker?.classList.add('is-invalid');
        if (errEl) {
            errEl.textContent = message || 'Please choose how you would like to send this.';
            errEl.classList.remove('d-none');
        }
        picker?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function setSubmitting(form, isSubmitting) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (!submitButton) {
            return;
        }
        if (isSubmitting) {
            if (!submitButton.dataset.kdrOriginalHtml) {
                submitButton.dataset.kdrOriginalHtml = submitButton.innerHTML;
            }
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa fa-spinner fa-spin me-2" aria-hidden="true"></i>Submitting…';
            return;
        }
        submitButton.disabled = false;
        if (submitButton.dataset.kdrOriginalHtml) {
            submitButton.innerHTML = submitButton.dataset.kdrOriginalHtml;
        }
    }

    function openExternalUrl(url) {
        if (!url) {
            return;
        }
        const opened = window.open(url, '_blank', 'noopener,noreferrer');
        if (!opened) {
            window.location.href = url;
        }
    }

    function applyFieldErrors(form, errors) {
        if (!errors || typeof errors !== 'object') {
            return;
        }
        Object.keys(errors).forEach(function (field) {
            const input = form.querySelector('[name="' + field + '"]');
            if (input) {
                input.classList.add('is-invalid');
            }
        });
        const firstKey = Object.keys(errors)[0];
        if (firstKey && errors[firstKey] && errors[firstKey][0]) {
            showChannelError(form, errors[firstKey][0]);
        }
    }

    async function submitWithExternalChannel(form, channel) {
        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: (form.method || 'POST').toUpperCase(),
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        });

        let data = {};
        try {
            data = await response.json();
        } catch (error) {
            data = {};
        }

        if (!response.ok) {
            setSubmitting(form, false);
            if (response.status === 422 && data.errors) {
                applyFieldErrors(form, data.errors);
                return;
            }
            showChannelError(form, data.message || 'Something went wrong. Please try again.');
            return;
        }

        if (data.open_url) {
            openExternalUrl(data.open_url);
        }

        if (data.redirect) {
            window.location.href = data.redirect;
            return;
        }

        setSubmitting(form, false);
        if (data.message && typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'success', title: 'Success', text: data.message });
        }
    }

    function initChannelForm(form) {
        if (form.dataset.kdrChannelInit === '1') {
            return;
        }
        form.dataset.kdrChannelInit = '1';

        form.querySelectorAll('input[name="channel"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                clearChannelError(form);
            });
        });

        form.addEventListener('submit', function (e) {
            const channelInput = form.querySelector('input[name="channel"]:checked');
            if (!channelInput) {
                e.preventDefault();
                showChannelError(form);
                return;
            }

            clearChannelError(form);

            if (!EXTERNAL_CHANNELS.includes(channelInput.value)) {
                setSubmitting(form, true);
                return;
            }

            e.preventDefault();
            setSubmitting(form, true);
            submitWithExternalChannel(form, channelInput.value).catch(function () {
                setSubmitting(form, false);
                showChannelError(form, 'Unable to submit right now. Please try again.');
            });
        });
    }

    function initAll() {
        document.querySelectorAll('form.kdr-channel-form').forEach(initChannelForm);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    document.addEventListener('shown.bs.modal', function (event) {
        const modal = event.target;
        if (!modal || !modal.querySelectorAll) {
            return;
        }
        modal.querySelectorAll('form.kdr-channel-form').forEach(initChannelForm);
    });
})();
