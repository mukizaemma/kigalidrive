{{-- Footer + header helpers — Kigali Drive brand (navy + gold) --}}
<style>
        .footer-wrapper.bg-title {
            background: linear-gradient(160deg, var(--kdr-navy-dark) 0%, var(--kdr-navy) 48%, var(--kdr-navy-mid) 100%);
            position: relative;
            overflow: hidden;
        }

        .footer-wrapper.bg-title::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 100% 0%, rgba(197, 160, 89, 0.12), transparent 55%),
                radial-gradient(ellipse 50% 40% at 0% 100%, rgba(197, 160, 89, 0.06), transparent 50%);
            pointer-events: none;
        }

        .widget-area {
            position: relative;
            z-index: 1;
        }

        .site-footer-enhanced .widget-area {
            padding-top: 4rem;
            padding-bottom: 3rem;
        }

        .footer-widget .widget_title {
            font-family: var(--kdr-display);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
            color: #fff;
            padding-bottom: 0.75rem;
            position: relative;
        }

        .footer-widget .widget_title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 36px;
            height: 2px;
            background: linear-gradient(90deg, var(--kdr-gold), var(--kdr-gold-light));
            border-radius: 2px;
        }

        .th-widget-about .about-text {
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 1.25rem;
            max-width: 320px;
        }

        .th-widget-about .about-logo img {
            transition: transform 0.25s ease;
        }

        .th-widget-about .about-logo:hover img {
            transform: scale(1.03);
        }

        /* Google reviews card — under logo */
        .footer-reviews-card {
            border: 1px solid rgba(197, 160, 89, 0.28);
            border-radius: var(--kdr-radius);
            padding: 1rem 1.15rem;
            background: rgba(0, 0, 0, 0.22);
            max-width: 320px;
        }

        .footer-reviews-card__head {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.6rem;
        }

        .footer-reviews-card__icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(197, 160, 89, 0.18);
            color: var(--kdr-gold-light);
            font-size: 0.9rem;
        }

        .footer-reviews-card__label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-reviews-card__stats {
            font-size: 1.3rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 0.45rem;
        }

        .footer-reviews-card__outof {
            font-size: 0.9rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.45);
        }

        .footer-reviews-card__dot {
            margin: 0 0.2rem;
            color: rgba(255, 255, 255, 0.35);
        }

        .footer-reviews-card__count {
            font-size: 0.88rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }

        .footer-reviews-card__empty {
            color: rgba(255, 255, 255, 0.65);
        }

        .footer-reviews-card__link {
            display: inline-block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--kdr-gold-light);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-reviews-card__link:hover {
            color: #fff;
        }

        /* Quick links */
        .footer-widget.widget_nav_menu ul.menu.footer-quick-links {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.45rem 1.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-widget.widget_nav_menu ul.menu.footer-quick-links li {
            margin: 0;
        }

        .footer-widget.widget_nav_menu ul.menu.footer-quick-links li a {
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            font-size: 0.92rem;
            transition: color 0.2s ease, padding-left 0.2s ease;
        }

        .footer-widget.widget_nav_menu ul.menu.footer-quick-links li a:hover {
            color: var(--kdr-gold-light);
            padding-left: 4px;
        }

        .footer-widget.widget_nav_menu ul.menu.footer-quick-links li a::before {
            display: none;
        }

        @media (max-width: 575px) {
            .footer-widget.widget_nav_menu ul.menu.footer-quick-links {
                grid-template-columns: 1fr;
            }
        }

        /* Contact column */
        .site-footer-enhanced .info-box_text {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.04);
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 10px;
            border: 1px solid rgba(255, 255, 255, 0.07);
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .site-footer-enhanced .info-box_text:hover {
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(197, 160, 89, 0.25);
        }

        .site-footer-enhanced .info-box_text .icon {
            background: rgba(197, 160, 89, 0.12);
            border: 1px solid rgba(197, 160, 89, 0.22);
            border-radius: 8px;
            padding: 8px;
            flex-shrink: 0;
        }

        .info-box_label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.45);
        }

        .info-box_text .details p,
        .info-box_text .details a {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }

        .info-box_text .details a:hover {
            color: var(--kdr-gold-light);
        }

        /* CTAs under contact */
        .footer-book-cta {
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-book-cta__label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 0.65rem;
        }

        .footer-book-cta__buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-book-btn.th-btn {
            width: 100%;
            justify-content: center;
            border-radius: 10px;
            padding: 0.7rem 1.15rem;
            font-size: 0.92rem;
            font-weight: 700;
            box-shadow: none;
        }

        .footer-book-btn.footer-book-btn--outline {
            background: transparent !important;
            color: #fff !important;
            border: 1px solid rgba(197, 160, 89, 0.45) !important;
            box-shadow: none !important;
        }

        .footer-book-btn.footer-book-btn--outline:hover {
            background: rgba(197, 160, 89, 0.12) !important;
            border-color: var(--kdr-gold) !important;
        }

        /* Social under Google reviews */
        .footer-widget--brand .footer-social-block {
            margin-top: 1rem;
            padding-top: 0;
            border-top: none;
        }

        .th-social--footer a {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            margin-right: 10px;
            margin-bottom: 8px;
            transition: background 0.2s ease, transform 0.2s ease, color 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .th-social--footer a:hover {
            background: linear-gradient(135deg, var(--kdr-gold), var(--kdr-gold-dark));
            color: var(--kdr-navy-dark);
            transform: translateY(-2px);
            border-color: transparent;
        }

        .copyright-wrap {
            background: rgba(0, 0, 0, 0.28);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1.25rem 0;
            position: relative;
            z-index: 1;
        }

        .copyright-text {
            color: rgba(255, 255, 255, 0.72);
            font-size: 0.875rem;
        }

        .copyright-text a {
            color: var(--kdr-gold-light);
            text-decoration: none;
        }

        .copyright-text a:hover {
            color: #fff;
        }

        /* Header user dropdown */
        .header-user-dropdown .sub-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: #fff;
            min-width: 200px;
            box-shadow: 0 8px 24px rgba(10, 29, 55, 0.12);
            border-radius: 8px;
            padding: 8px 0;
            margin-top: 8px;
            z-index: 999;
            list-style: none;
        }

        .header-user-dropdown .menu-item-has-children:hover .sub-menu {
            display: block;
        }

        .header-user-dropdown .sub-menu li a,
        .header-user-dropdown .sub-menu li button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 10px 18px;
            color: var(--kdr-navy);
            text-decoration: none;
            border: none;
            background: none;
            cursor: pointer;
        }

        .header-user-dropdown .sub-menu li a:hover,
        .header-user-dropdown .sub-menu li button:hover {
            background: var(--kdr-cream);
            color: var(--kdr-gold-dark);
        }

        @media (max-width: 991px) {
            .header-logo img { width: 120px !important; max-width: 120px; height: auto; }
            .site-footer-enhanced .widget-area { padding-top: 3rem; padding-bottom: 2.5rem; }
            .footer-reviews-card { max-width: 100%; }
        }

        @media (max-width: 575px) {
            .header-logo img { width: 95px !important; }
            .mobile-logo img { width: 90px !important; }
        }

        .th-header .menu-area { position: relative; z-index: 1050; }
        .th-header .main-menu .sub-menu { z-index: 1060; }

        /* WhatsApp — keep brand green for recognition */
        .whatsapp-float {
            position: fixed;
            bottom: 24px;
            left: 24px;
            width: 56px;
            height: 56px;
            background: #25d366;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.45);
            z-index: 1000;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
        }

        .whatsapp-float:hover {
            transform: scale(1.06);
            color: #fff;
            box-shadow: 0 8px 28px rgba(37, 211, 102, 0.55);
        }

        @media (max-width: 767px) {
            .whatsapp-float {
                width: 52px;
                height: 52px;
                bottom: 18px;
                left: 18px;
            }
        }
</style>
