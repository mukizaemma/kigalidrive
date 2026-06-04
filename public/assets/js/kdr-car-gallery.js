/**
 * Car detail gallery: main image + thumbnail strip.
 */
(function () {
    function initGallery(root) {
        if (!root || root.dataset.kdrGalleryInit === '1') {
            return;
        }
        root.dataset.kdrGalleryInit = '1';

        const main = root.querySelector('#kdrCarGalleryMain');
        const thumbs = Array.from(root.querySelectorAll('.kdr-car-gallery__thumb'));
        if (!main || thumbs.length === 0) {
            return;
        }

        let activeIndex = 0;

        function setActive(index) {
            if (index < 0) {
                index = thumbs.length - 1;
            }
            if (index >= thumbs.length) {
                index = 0;
            }
            activeIndex = index;
            const thumb = thumbs[index];
            const src = thumb.getAttribute('data-gallery-src');
            if (src) {
                main.src = src;
            }
            thumbs.forEach(function (btn, i) {
                const on = i === index;
                btn.classList.toggle('is-active', on);
                btn.setAttribute('aria-selected', on ? 'true' : 'false');
            });
        }

        thumbs.forEach(function (thumb, index) {
            thumb.addEventListener('click', function () {
                setActive(index);
            });
        });

        const prev = root.querySelector('[data-gallery-prev]');
        const next = root.querySelector('[data-gallery-next]');
        if (prev) {
            prev.addEventListener('click', function () {
                setActive(activeIndex - 1);
            });
        }
        if (next) {
            next.addEventListener('click', function () {
                setActive(activeIndex + 1);
            });
        }
    }

    function initAll() {
        document.querySelectorAll('.kdr-car-gallery').forEach(initGallery);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();
