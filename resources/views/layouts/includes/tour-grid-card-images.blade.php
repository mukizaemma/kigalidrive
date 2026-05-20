{{-- Uniform cover image height for property/hotel grid cards (not list view). --}}
<style>
    .tour-box:not(.style-flex) > .tour-box_img {
        aspect-ratio: 16 / 10;
    }

    .tour-box:not(.style-flex) > .tour-box_img > img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
    }

    .tour-box:not(.style-flex) > .tour-box_img::before {
        z-index: 1;
        pointer-events: none;
    }

    .tour-box:not(.style-flex) > .tour-box_img .global-img::after {
        z-index: 2;
    }
</style>
