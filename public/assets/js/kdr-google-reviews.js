/**
 * "Read more" for truncated Google review cards.
 */
(function () {
    function initCard(card) {
        const textEl = card.querySelector('[data-kdr-review-text]');
        const moreBtn = card.querySelector('[data-kdr-review-more]');
        if (!textEl || !moreBtn) return;

        requestAnimationFrame(function () {
            if (textEl.scrollHeight > textEl.clientHeight + 2) {
                moreBtn.classList.remove('d-none');
                moreBtn.hidden = false;
            }
        });

        moreBtn.addEventListener('click', function () {
            const expanded = card.classList.toggle('is-expanded');
            moreBtn.textContent = expanded ? 'Show less' : 'Read more';
        });
    }

    function initAll() {
        document.querySelectorAll('[data-kdr-review-card]').forEach(initCard);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();
