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
            return false;
        }
        const opened = window.open(url, '_blank', 'noopener,noreferrer');
        if (!opened) {
            window.location.href = url;
            return true;
        }
        return true;
    }

    function closeParentModal(form) {
        const modalEl = form.closest('.modal');
        if (!modalEl || typeof bootstrap === 'undefined') {
            return;
        }
        const instance = bootstrap.Modal.getInstance(modalEl);
        if (instance) {
            instance.hide();
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

    function showSuccess(form, message) {
        if (message && typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Saved',
                text: message,
                confirmButtonColor: '#c5a059',
            });
            return;
        }
        if (message) {
            alert(message);
        }
    }

    function afterSuccessfulSubmit(form, data) {
        if (data.open_url) {
            openExternalUrl(data.open_url);
        }

        closeParentModal(form);

        if (data.redirect) {
            setTimeout(function () {
                window.location.href = data.redirect;
            }, data.open_url ? 400 : 0);
            return;
        }

        setSubmitting(form, false);
        showSuccess(form, data.message);
    }

    async function submitWithExternalChannel(form) {
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

        afterSuccessfulSubmit(form, data);
    }

    function initChannelForm(form) {
        if (form.dataset.kdrChannelInit === '1') {
            return;
        }
        form.dataset.kdrChannelInit = '1';

        const hasChannels = form.dataset.kdrHasChannels !== '0';
        const submitButton = form.querySelector('button[type="submit"]');

        if (!hasChannels && submitButton) {
            submitButton.disabled = true;
        }

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
            submitWithExternalChannel(form).catch(function () {
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
