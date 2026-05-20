@extends('layouts.public')

@section('title', 'Welcome')

@section('content')

<style>
    body { overflow-x: hidden; }
    .lp-max  { max-width: 1200px; margin-left: auto; margin-right: auto; }
    .lp-px   { padding-left: 28px; padding-right: 28px; }

    /* ── Scroll reveal ───────────────────────────────────────────── */
    .lp-reveal {
        opacity: 0; transform: translateY(18px);
        transition: opacity 0.55s ease, transform 0.55s ease;
    }
    .lp-reveal.lp-vis { opacity: 1; transform: none; }
    .lp-stagger { }
    .lp-stagger > * {
        opacity: 0; transform: translateY(14px);
        transition: opacity 0.45s ease, transform 0.45s ease;
    }
    .lp-stagger.lp-vis > *:nth-child(1) { opacity:1;transform:none;transition-delay:.04s; }
    .lp-stagger.lp-vis > *:nth-child(2) { opacity:1;transform:none;transition-delay:.12s; }
    .lp-stagger.lp-vis > *:nth-child(3) { opacity:1;transform:none;transition-delay:.20s; }
    .lp-stagger.lp-vis > *:nth-child(4) { opacity:1;transform:none;transition-delay:.28s; }
    /* Plans & coaches render immediately */
    .lp-plans-grid.lp-stagger > *,
    .lp-coaches-grid.lp-stagger > * { opacity:1; transform:none; }

    /* ══════════════════════════════════════════════════════════════
       1. HERO
    ══════════════════════════════════════════════════════════════ */
    .lp-hero {
        position: relative;
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
    }
    .lp-hero__bg {
        position: absolute; inset: 0; z-index: 0;
        background-image: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1600&q=80');
        background-size: cover; background-position: center;
        transform: scale(1.04);
        transition: transform 8s ease-out;
    }
    .lp-hero.loaded .lp-hero__bg { transform: scale(1); }
    .lp-hero__overlay {
        position: absolute; inset: 0; z-index: 1;
        background: linear-gradient(170deg, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0.60) 55%, rgba(0,0,0,0.88) 100%);
    }
    /* Diagonal gold slash */
    .lp-hero__slash {
        position: absolute; bottom: 80px; left: 0; right: 0; z-index: 2;
        height: 3px;
        background: var(--brand-gold);
        transform: rotate(-0.6deg);
        box-shadow: 0 0 24px rgba(232,160,32,0.35);
    }
    .lp-hero__content {
        position: relative; z-index: 3;
        text-align: center; padding: 40px 28px 120px;
        max-width: 860px;
    }
    .lp-hero__eyebrow {
        display: inline-block;
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.18em; color: var(--brand-gold);
        border: 1px solid var(--brand-gold-border);
        padding: 5px 16px; border-radius: 100px;
        margin-bottom: 24px;
    }
    .lp-hero__heading {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: clamp(64px, 9vw, 120px);
        font-weight: 900; text-transform: uppercase;
        line-height: 0.92; letter-spacing: -0.02em;
        color: #f8f8f8;
        margin-bottom: 24px;
    }
    .lp-hero__heading em {
        font-style: normal;
        color: var(--brand-gold);
        display: block;
    }
    .lp-hero__sub {
        font-size: 18px; color: rgba(255,255,255,0.7);
        line-height: 1.65; max-width: 520px;
        margin: 0 auto 36px;
    }
    .lp-hero__cta { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; }
    /* Scroll chevron */
    .lp-hero__scroll {
        position: absolute; bottom: 28px; left: 50%; transform: translateX(-50%);
        z-index: 3; display: flex; flex-direction: column; align-items: center; gap: 4px;
        color: rgba(255,255,255,0.4); font-size: 10px; letter-spacing: 0.1em;
        text-transform: uppercase;
        animation: lpBounce 2s infinite;
    }
    .lp-hero__scroll svg { color: var(--brand-gold); opacity: 0.7; }
    @keyframes lpBounce {
        0%, 100% { transform: translateX(-50%) translateY(0); }
        50%       { transform: translateX(-50%) translateY(6px); }
    }

    /* ══════════════════════════════════════════════════════════════
       MARQUEE
    ══════════════════════════════════════════════════════════════ */
    .lp-marquee {
        background: var(--surface-1);
        border-top: 1px solid rgba(255,255,255,0.05);
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding: 14px 0; overflow: hidden;
    }
    .lp-marquee__track {
        display: flex; gap: 52px; align-items: center;
        animation: lpMarquee 26s linear infinite; width: max-content;
    }
    .lp-marquee:hover .lp-marquee__track { animation-play-state: paused; }
    .lp-marquee__item {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--text-muted); white-space: nowrap;
        display: flex; align-items: center; gap: 10px;
    }
    .lp-marquee__item::before { content: '✦'; color: var(--brand-gold); font-size: 8px; }
    @keyframes lpMarquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    /* ══════════════════════════════════════════════════════════════
       2. STATS BAR
    ══════════════════════════════════════════════════════════════ */
    .lp-stats {
        background: #060606;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        padding: 64px 28px;
    }
    .lp-stats__grid {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 0; max-width: 900px; margin: 0 auto;
    }
    .lp-stat {
        text-align: center; padding: 0 24px;
        border-right: 1px solid #141414;
    }
    .lp-stat:last-child { border-right: none; }
    .lp-stat__overline {
        display: block; width: 32px; height: 2px;
        background: var(--brand-gold); margin: 0 auto 16px;
        border-radius: 2px;
    }
    .lp-stat__number {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: clamp(48px, 6vw, 72px);
        font-weight: 900; line-height: 1;
        color: #f0f0f0; display: block;
        letter-spacing: -0.02em;
    }
    .lp-stat__suffix {
        color: var(--brand-gold); font-size: 0.7em;
    }
    .lp-stat__label {
        font-size: 11px; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--text-muted);
        margin-top: 8px; display: block;
    }

    /* ══════════════════════════════════════════════════════════════
       3. FEATURES / WHY AMIGOS
    ══════════════════════════════════════════════════════════════ */
    .lp-section { padding: 96px 28px; }
    .lp-section--dark    { background: #060606; }
    .lp-section--surface { background: #090909; }

    .lp-heading {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: clamp(36px, 4vw, 58px);
        font-weight: 900; text-transform: uppercase;
        line-height: 0.96; letter-spacing: -0.01em;
        color: #ebebeb; margin-bottom: 14px;
    }
    .lp-sub {
        font-size: 15px; color: var(--text-secondary);
        line-height: 1.7; max-width: 540px;
    }

    .lp-features__layout {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 48px; align-items: center; margin-top: 56px;
    }
    .lp-features__grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 14px;
    }
    .lp-feature-tile {
        background: #090909; border: 1px solid #181818;
        border-radius: 12px; padding: 24px 20px;
        position: relative; overflow: hidden;
        transition: border-color 0.25s, background 0.25s, transform 0.25s;
    }
    .lp-feature-tile:hover {
        border-color: var(--brand-gold-border);
        background: #0f0f0f;
        transform: translateY(-2px);
    }
    .lp-feature-tile::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 3px; background: var(--brand-gold);
        transform: scaleY(0); transform-origin: bottom;
        transition: transform 0.25s ease;
        border-radius: 0 2px 2px 0;
    }
    .lp-feature-tile:hover::before { transform: scaleY(1); }
    .lp-feature-tile__icon {
        width: 36px; height: 36px; margin-bottom: 14px;
        color: var(--brand-gold);
    }
    .lp-feature-tile__title {
        font-size: 14px; font-weight: 700; color: #e0e0e0; margin-bottom: 6px;
    }
    .lp-feature-tile__desc { font-size: 12px; color: var(--text-muted); line-height: 1.6; }

    .lp-features__img-wrap {
        border-radius: 14px; overflow: hidden;
        border: 1px solid rgba(232,160,32,0.15);
        box-shadow: -4px 0 0 0 var(--brand-gold), 0 32px 80px rgba(0,0,0,0.6);
        aspect-ratio: 3/4; position: relative;
    }
    .lp-features__img-wrap img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform 0.6s ease;
    }
    .lp-features__img-wrap:hover img { transform: scale(1.03); }
    .lp-features__img-wrap::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 60%);
    }

    /* ══════════════════════════════════════════════════════════════
       4. TRAINING PROGRAMS
    ══════════════════════════════════════════════════════════════ */
    .lp-programs__grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 14px; margin-top: 48px;
    }
    .lp-program-card {
        position: relative; border-radius: 14px; overflow: hidden;
        aspect-ratio: 4/3;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        cursor: pointer;
    }
    .lp-program-card__img {
        position: absolute; inset: 0;
        background-size: cover; background-position: center;
        transition: transform 0.5s ease;
    }
    .lp-program-card:hover .lp-program-card__img { transform: scale(1.04); }
    .lp-program-card__overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.25) 55%, transparent 100%);
    }
    .lp-program-card__content {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 24px 22px;
    }
    .lp-program-card__label {
        display: block; font-size: 9px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.16em;
        color: var(--brand-gold); margin-bottom: 6px;
    }
    .lp-program-card__title {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: 26px; font-weight: 900; text-transform: uppercase;
        color: #f8f8f8; line-height: 1; margin-bottom: 8px;
    }
    .lp-program-card__desc { font-size: 12px; color: rgba(255,255,255,0.6); line-height: 1.55; margin-bottom: 12px; }
    .lp-program-card__link {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.08em; color: var(--brand-gold);
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 5px;
        transition: gap 0.2s;
    }
    .lp-program-card:hover .lp-program-card__link { gap: 9px; }

    /* ══════════════════════════════════════════════════════════════
       5. PLANS
    ══════════════════════════════════════════════════════════════ */
    .lp-plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 320px));
        justify-content: center;
        gap: 14px; margin-top: 48px;
    }
    .lp-plan-card {
        background: #090909; border: 1px solid #181818;
        border-radius: 16px; padding: 32px 26px;
        position: relative; display: flex; flex-direction: column;
        transition: border-color 0.22s, transform 0.22s, box-shadow 0.22s;
        overflow: hidden;
    }
    .lp-plan-card:hover { border-color: #2a2a2a; transform: translateY(-3px); }
    .lp-plan-card--featured {
        border-color: rgba(232,160,32,0.4);
        background: #0c0c0c;
        box-shadow: 0 0 40px rgba(232,160,32,0.08);
    }
    .lp-plan-card--featured:hover {
        box-shadow: 0 0 60px rgba(232,160,32,0.14);
    }
    /* Diagonal ribbon */
    .lp-plan-card--featured::before {
        content: 'Most Popular';
        position: absolute; top: 18px; right: -28px;
        background: var(--brand-gold); color: #000;
        font-size: 9px; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.08em; padding: 4px 36px;
        transform: rotate(38deg);
    }
    .lp-plan__name {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--text-muted); margin-bottom: 12px;
    }
    .lp-plan__price {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: 72px; font-weight: 900; line-height: 1;
        color: #f0f0f0; margin-bottom: 2px; letter-spacing: -0.02em;
    }
    .lp-plan__currency {
        font-size: 24px; font-weight: 400; color: var(--text-muted);
        vertical-align: super; font-family: inherit;
    }
    .lp-plan__period { font-size: 12px; color: #3a3a3a; margin-bottom: 24px; }
    .lp-plan__benefits {
        list-style: none; display: flex; flex-direction: column;
        gap: 10px; margin-bottom: 28px; flex: 1;
    }
    .lp-plan__benefits li {
        display: flex; align-items: flex-start; gap: 9px;
        font-size: 13px; color: #686868;
    }
    .lp-plan__check {
        color: var(--brand-gold); font-weight: 700;
        font-size: 12px; flex-shrink: 0; margin-top: 1px;
    }
    .lp-plan-btn {
        display: block; width: 100%; text-align: center;
        font-size: 12px; font-weight: 700; letter-spacing: 0.04em;
        padding: 12px 0; border-radius: 8px;
        border: 1px solid #222; color: #aaa;
        text-decoration: none; background: transparent;
        transition: border-color 0.2s, color 0.2s;
    }
    .lp-plan-btn:hover { border-color: var(--brand-gold-border); color: var(--brand-gold); }
    .lp-plan-card--featured .lp-plan-btn {
        background: var(--brand-gold); color: #000; border-color: transparent;
        font-size: 13px;
    }
    .lp-plan-card--featured .lp-plan-btn:hover { background: var(--brand-gold-hover); }
    .lp-empty-card {
        grid-column: 1/-1; max-width: 460px; width: 100%; margin: 0 auto;
        background: rgba(232,160,32,0.04); border: 1px dashed rgba(232,160,32,0.22);
        border-radius: 14px; padding: 32px 24px; text-align: center;
    }
    .lp-empty-card h3 { color: #c0c0c0; font-size: 15px; font-weight: 700; margin-bottom: 6px; }
    .lp-empty-card p  { color: var(--text-muted); font-size: 13px; line-height: 1.6; }

    /* ══════════════════════════════════════════════════════════════
       6. COACHES
    ══════════════════════════════════════════════════════════════ */
    .lp-coaches-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 300px));
        justify-content: center;
        gap: 14px; margin-top: 48px;
    }
    .lp-coach-card {
        background: #090909; border: 1px solid #181818;
        border-radius: 14px; padding: 26px 22px;
        transition: border-color 0.22s, transform 0.22s;
    }
    .lp-coach-card:hover { border-color: #252525; transform: translateY(-2px); }
    .lp-coach-avatar {
        width: 80px; height: 80px; border-radius: 50%;
        background: var(--surface-3);
        border: 2px solid var(--brand-gold);
        box-shadow: 0 0 0 5px rgba(232,160,32,0.12);
        overflow: hidden; margin-bottom: 14px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .lp-coach-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .lp-coach-avatar span {
        font-size: 28px; font-weight: 700; color: var(--text-muted);
        font-family: 'Barlow Condensed', system-ui, sans-serif;
    }
    .lp-coach-name  { font-size: 16px; font-weight: 700; color: #e0e0e0; margin-bottom: 6px; }
    .lp-coach-stars { display: flex; gap: 3px; margin-bottom: 10px; }
    .lp-coach-star  { width: 13px; height: 13px; fill: var(--brand-gold); }
    .lp-coach-tags  { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 10px; }
    .lp-coach-tag {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.07em; color: var(--brand-gold);
        background: var(--brand-gold-dim); border: 1px solid var(--brand-gold-border);
        padding: 3px 8px; border-radius: 100px;
    }
    .lp-coach-bio {
        font-size: 12px; color: var(--text-muted); line-height: 1.6;
    }
    .lp-coach-bio--clamped {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .lp-coach-readmore {
        background: none; border: none; padding: 0; cursor: pointer;
        font-size: 11px; color: var(--brand-gold); font-weight: 600;
        margin-top: 4px; display: block;
        transition: opacity 0.2s;
    }
    .lp-coach-readmore:hover { opacity: 0.7; }

    /* ══════════════════════════════════════════════════════════════
       7. TESTIMONIALS
    ══════════════════════════════════════════════════════════════ */
    .lp-testimonials-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 14px; margin-top: 48px;
    }
    .lp-testimonial-card {
        background: #0c0c0c; border: 1px solid #1c1c1c;
        border-radius: 14px; padding: 28px 24px;
        position: relative; overflow: hidden;
        transition: border-color 0.22s, transform 0.22s;
    }
    .lp-testimonial-card:hover { border-color: rgba(232,160,32,0.18); transform: translateY(-2px); }
    .lp-testimonial-card__quote-decor {
        font-size: 80px; line-height: 1; color: var(--brand-gold);
        opacity: 0.18; font-family: Georgia, serif;
        position: absolute; top: 8px; left: 18px;
        pointer-events: none; user-select: none;
    }
    .lp-testimonial-card__stars {
        display: flex; gap: 3px; margin-bottom: 16px; position: relative; z-index: 1;
    }
    .lp-testimonial-star { width: 14px; height: 14px; fill: var(--brand-gold); }
    .lp-testimonial-card__quote {
        font-size: 14px; font-style: italic; color: #b0b0b0;
        line-height: 1.7; margin-bottom: 20px;
        position: relative; z-index: 1;
    }
    .lp-testimonial-card__footer { display: flex; align-items: center; gap: 12px; }
    .lp-testimonial-card__avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: var(--surface-3); border: 1px solid #2a2a2a;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: 16px; font-weight: 700; color: var(--text-muted);
        flex-shrink: 0;
    }
    .lp-testimonial-card__name {
        font-size: 14px; font-weight: 700; color: #e0e0e0;
    }
    .lp-testimonial-card__role {
        font-size: 11px; color: var(--brand-gold);
        font-weight: 600; letter-spacing: 0.04em;
    }

    /* ══════════════════════════════════════════════════════════════
       8. CTA BANNER
    ══════════════════════════════════════════════════════════════ */
    .lp-cta-banner {
        position: relative; min-height: 400px;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
    }
    .lp-cta-banner__bg {
        position: absolute; inset: 0;
        background-image: url('https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=1400&q=80');
        background-size: cover; background-position: center 30%;
    }
    .lp-cta-banner__overlay {
        position: absolute; inset: 0;
        background: rgba(0,0,0,0.78);
    }
    .lp-cta-banner__content {
        position: relative; z-index: 1;
        text-align: center; padding: 80px 28px;
        max-width: 760px;
    }
    .lp-cta-banner__heading {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: clamp(48px, 6vw, 80px);
        font-weight: 900; text-transform: uppercase;
        line-height: 0.92; letter-spacing: -0.015em;
        color: #f8f8f8; margin-bottom: 16px;
    }
    .lp-cta-banner__heading span { color: var(--brand-gold); }
    .lp-cta-banner__sub {
        font-size: 16px; color: rgba(255,255,255,0.6);
        line-height: 1.65; margin-bottom: 32px;
    }

    /* ══════════════════════════════════════════════════════════════
       CONTACT
    ══════════════════════════════════════════════════════════════ */
    .lp-info-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 10px; margin-bottom: 40px;
    }
    .lp-info-card {
        background: #090909; border: 1px solid #181818;
        border-radius: 11px; padding: 20px 18px;
    }
    .lp-info-card h4 {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.14em; color: var(--brand-gold); margin-bottom: 8px;
    }
    .lp-info-card p { font-size: 13px; color: var(--text-secondary); line-height: 1.55; }

    /* ══════════════════════════════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════════════════════════════ */
    @media (max-width: 1024px) {
        .lp-features__layout { grid-template-columns: 1fr; }
        .lp-features__img-wrap { aspect-ratio: 16/7; order: -1; }
        .lp-programs__grid { grid-template-columns: 1fr; max-width: 500px; margin-left: auto; margin-right: auto; }
        .lp-plans-grid { grid-template-columns: 1fr; max-width: 380px; margin-left: auto; margin-right: auto; }
        .lp-coaches-grid { grid-template-columns: 1fr 1fr; }
        .lp-testimonials-grid { grid-template-columns: 1fr; max-width: 480px; margin-left: auto; margin-right: auto; }
        .lp-info-grid { grid-template-columns: 1fr; }
        .lp-stats__grid { grid-template-columns: repeat(2, 1fr); gap: 28px 0; }
        .lp-stat { border-right: none; border-bottom: 1px solid #141414; padding-bottom: 28px; }
        .lp-stat:nth-child(odd) { border-right: 1px solid #141414; }
        .lp-stat:last-child, .lp-stat:nth-last-child(2):nth-child(odd) { border-bottom: none; }
    }
    @media (max-width: 768px) {
        .lp-section { padding: 64px 18px; }
        .lp-hero__heading { font-size: 52px; }
        .lp-coaches-grid { grid-template-columns: 1fr; }
        .lp-plans-grid { grid-template-columns: 1fr; max-width: 100%; }
        .lp-features__grid { grid-template-columns: 1fr; }
        .lp-stats__grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .lp-stats__grid { grid-template-columns: 1fr 1fr; }
        .lp-stat { border-right: none; border-bottom: 1px solid #141414; }
        .lp-stat:nth-child(odd) { border-right: 1px solid #141414; }
    }
</style>

{{-- ═══ 1. HERO ══════════════════════════════════════════════════════════════ --}}
<section class="lp-hero" id="lpHero">
    <div class="lp-hero__bg" id="lpHeroBg"></div>
    <div class="lp-hero__overlay"></div>
    <div class="lp-hero__slash"></div>

    <div class="lp-hero__content">
        <span class="lp-hero__eyebrow">Amigos Fitness Gym</span>

        <h1 class="lp-hero__heading">
            {!! nl2br(e($content['hero_title'])) !!}
        </h1>

        <p class="lp-hero__sub">{{ $content['hero_subtitle'] }}</p>

        <div class="lp-hero__cta">
            @guest
                <a href="{{ route('register') }}" class="btn-gold" style="font-size:14px; padding:13px 28px;">
                    Become a Member
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="#plans" class="btn-outline" style="font-size:14px; padding:13px 28px;">View Plans</a>
            @else
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}" class="btn-gold" style="font-size:14px; padding:13px 28px;">
                    Go to My Dashboard →
                </a>
            @endguest
        </div>
    </div>

    <div class="lp-hero__scroll" aria-hidden="true">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</section>

{{-- ═══ MARQUEE ══════════════════════════════════════════════════════════════ --}}
<div class="lp-marquee" aria-hidden="true">
    <div class="lp-marquee__track">
        <span class="lp-marquee__item">Certified Coaches</span>
        <span class="lp-marquee__item">Premium Equipment</span>
        <span class="lp-marquee__item">Group Classes</span>
        <span class="lp-marquee__item">Personal Training</span>
        <span class="lp-marquee__item">Nutrition Guidance</span>
        <span class="lp-marquee__item">Recovery Zone</span>
        <span class="lp-marquee__item">Certified Coaches</span>
        <span class="lp-marquee__item">Premium Equipment</span>
        <span class="lp-marquee__item">Group Classes</span>
        <span class="lp-marquee__item">Personal Training</span>
        <span class="lp-marquee__item">Nutrition Guidance</span>
        <span class="lp-marquee__item">Recovery Zone</span>
    </div>
</div>

{{-- ═══ 2. STATS BAR ═══════════════════════════════════════════════════════ --}}
<div class="lp-stats" id="lpStats">
    <div class="lp-stats__grid lp-stagger" id="lpStatsGrid">
        <div class="lp-stat">
            <span class="lp-stat__overline"></span>
            <span class="lp-stat__number">
                <span class="lp-counter" data-target="500" data-suffix="+">0+</span>
            </span>
            <span class="lp-stat__label">Active Members</span>
        </div>
        <div class="lp-stat">
            <span class="lp-stat__overline"></span>
            <span class="lp-stat__number">
                <span class="lp-counter" data-target="5" data-suffix="+">0+</span>
            </span>
            <span class="lp-stat__label">Years Open</span>
        </div>
        <div class="lp-stat">
            <span class="lp-stat__overline"></span>
            <span class="lp-stat__number">
                <span class="lp-counter" data-target="20" data-suffix="+">0+</span>
            </span>
            <span class="lp-stat__label">Classes / Week</span>
        </div>
        <div class="lp-stat">
            <span class="lp-stat__overline"></span>
            <span class="lp-stat__number">
                <span class="lp-counter" data-target="10" data-suffix="+">0+</span>
            </span>
            <span class="lp-stat__label">Expert Coaches</span>
        </div>
    </div>
</div>

{{-- ═══ 3. WHY AMIGOS ═══════════════════════════════════════════════════════ --}}
<section class="lp-section lp-section--dark">
    <div class="lp-max lp-px">
        <span class="section-label lp-reveal">Why Choose Us</span>
        <h2 class="lp-heading lp-reveal">Built For <em style="color:var(--brand-gold);font-style:normal;">Champions</em></h2>
        <p class="lp-sub lp-reveal">Everything you need to train harder, recover smarter, and build the body you've always wanted.</p>

        <div class="lp-features__layout">
            <div class="lp-features__grid lp-stagger lp-vis">

                {{-- Equipment --}}
                <div class="lp-feature-tile">
                    <svg class="lp-feature-tile__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="10" width="4" height="4" rx="1"/><rect x="18" y="10" width="4" height="4" rx="1"/>
                        <rect x="6" y="8" width="2" height="8" rx="1"/><rect x="16" y="8" width="2" height="8" rx="1"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    <p class="lp-feature-tile__title">State-of-the-Art Equipment</p>
                    <p class="lp-feature-tile__desc">Commercial-grade machines and free weights updated every year.</p>
                </div>

                {{-- Coaches --}}
                <div class="lp-feature-tile">
                    <svg class="lp-feature-tile__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                        <path d="M16 12l2 2 4-4" stroke-width="2"/>
                    </svg>
                    <p class="lp-feature-tile__title">Expert Coaches</p>
                    <p class="lp-feature-tile__desc">Certified trainers who design programs around your personal goals.</p>
                </div>

                {{-- Schedules --}}
                <div class="lp-feature-tile">
                    <svg class="lp-feature-tile__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/>
                        <line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="15" x2="10" y2="15"/><line x1="12" y1="15" x2="16" y2="15"/>
                    </svg>
                    <p class="lp-feature-tile__title">Flexible Schedules</p>
                    <p class="lp-feature-tile__desc">Morning, noon, and evening classes to fit any lifestyle.</p>
                </div>

                {{-- Community --}}
                <div class="lp-feature-tile">
                    <svg class="lp-feature-tile__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <p class="lp-feature-tile__title">Community Support</p>
                    <p class="lp-feature-tile__desc">A tight-knit gym family that keeps you accountable and motivated.</p>
                </div>

            </div>

            <div class="lp-features__img-wrap lp-reveal">
                <img
                    src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800&q=80"
                    alt="Strength training at Amigos Fitness Gym"
                    loading="lazy"
                >
            </div>
        </div>
    </div>
</section>

{{-- ═══ 4. TRAINING PROGRAMS ════════════════════════════════════════════════ --}}
<section class="lp-section lp-section--surface">
    <div class="lp-max lp-px">
        <span class="section-label lp-reveal">What We Offer</span>
        <h2 class="lp-heading lp-reveal">Training <em style="color:var(--brand-gold);font-style:normal;">Programs</em></h2>
        <p class="lp-sub lp-reveal">Choose the training style that matches your goals and get started today.</p>

        <div class="lp-programs__grid lp-stagger lp-vis">

            <div class="lp-program-card">
                <div class="lp-program-card__img" style="background-image:url('https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=600&q=80')"></div>
                <div class="lp-program-card__overlay"></div>
                <div class="lp-program-card__content">
                    <span class="lp-program-card__label">Program</span>
                    <p class="lp-program-card__title">Strength Training</p>
                    <p class="lp-program-card__desc">Build raw power and lean muscle with periodised barbell and dumbbell programming.</p>
                    <a href="#plans" class="lp-program-card__link">
                        Explore
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="lp-program-card">
                <div class="lp-program-card__img" style="background-image:url('https://images.unsplash.com/photo-1483721310020-03333e577078?w=600&q=80')"></div>
                <div class="lp-program-card__overlay"></div>
                <div class="lp-program-card__content">
                    <span class="lp-program-card__label">Program</span>
                    <p class="lp-program-card__title">Cardio &amp; HIIT</p>
                    <p class="lp-program-card__desc">Torch calories, improve endurance, and boost metabolic rate with high-intensity intervals.</p>
                    <a href="#plans" class="lp-program-card__link">
                        Explore
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="lp-program-card">
                <div class="lp-program-card__img" style="background-image:url('https://images.unsplash.com/photo-1599058945522-28d584b6f0ff?w=600&q=80')"></div>
                <div class="lp-program-card__overlay"></div>
                <div class="lp-program-card__content">
                    <span class="lp-program-card__label">Program</span>
                    <p class="lp-program-card__title">Group Classes</p>
                    <p class="lp-program-card__desc">Train with energy in our daily group sessions — Zumba, BodyPump, Yoga, and more.</p>
                    <a href="#plans" class="lp-program-card__link">
                        Explore
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══ 5. PLANS ════════════════════════════════════════════════════════════ --}}
<section id="plans" class="lp-section lp-section--dark">
    <div class="lp-max lp-px">
        <span class="section-label lp-reveal">Membership</span>
        <h2 class="lp-heading lp-reveal">Choose Your Plan</h2>
        <p class="lp-sub lp-reveal">Flexible options designed for every training goal and schedule.</p>

        <div class="lp-plans-grid lp-stagger lp-vis">
            @forelse($plans as $plan)
                <div class="lp-plan-card {{ $loop->iteration === 2 ? 'lp-plan-card--featured' : '' }}">
                    <p class="lp-plan__name">{{ $plan->name }}</p>
                    <div class="lp-plan__price">
                        <span class="lp-plan__currency">₱</span>{{ number_format($plan->price, 0) }}
                    </div>
                    <p class="lp-plan__period">/ {{ $plan->duration_days }}-day access</p>
                    <ul class="lp-plan__benefits">
                        @foreach(($plan->benefits ?? []) as $benefit)
                            <li>
                                <span class="lp-plan__check">✓</span>
                                <span>{{ $benefit }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @guest
                        <a href="{{ route('register', ['plan' => $plan->id]) }}" class="lp-plan-btn">Get Started</a>
                    @else
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}" class="lp-plan-btn">
                            Go to Dashboard →
                        </a>
                    @endguest
                </div>
            @empty
                <div class="lp-empty-card">
                    <h3>No membership plans yet</h3>
                    <p>Plan cards will appear here automatically after plans are added in the admin plan manager.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══ 6. COACHES ══════════════════════════════════════════════════════════ --}}
<section id="coaches" class="lp-section lp-section--surface">
    <div class="lp-max lp-px">
        <span class="section-label lp-reveal">Our Team</span>
        <h2 class="lp-heading lp-reveal">Meet Your Coaches</h2>
        <p class="lp-sub lp-reveal">World-class coaches dedicated to helping you reach your peak performance.</p>

        <div class="lp-coaches-grid lp-stagger lp-vis">
            @forelse($coaches as $coach)
                <div class="lp-coach-card" x-data="{ expanded: false }">
                    <div class="lp-coach-avatar">
                        @if($coach->photo)
                            <img src="{{ asset('storage/' . $coach->photo) }}" alt="{{ $coach->name }}">
                        @else
                            <span>{{ strtoupper(substr($coach->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <h3 class="lp-coach-name">{{ $coach->name }}</h3>
                    {{-- 5-star SVG rating --}}
                    <div class="lp-coach-stars" aria-label="5 stars">
                        @for($s = 0; $s < 5; $s++)
                            <svg class="lp-coach-star" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <div class="lp-coach-tags">
                        @foreach(($coach->specializations ?? []) as $spec)
                            <span class="lp-coach-tag">{{ $spec }}</span>
                        @endforeach
                    </div>
                    <p class="lp-coach-bio" :class="expanded ? '' : 'lp-coach-bio--clamped'">{{ $coach->bio }}</p>
                    @if(strlen($coach->bio ?? '') > 80)
                        <button class="lp-coach-readmore" @click="expanded = !expanded" x-text="expanded ? 'Show less' : 'Read more'">Read more</button>
                    @endif
                </div>
            @empty
                <div class="lp-empty-card">
                    <h3>No coaches yet</h3>
                    <p>Coach cards will appear here after coaches are added in the admin coach manager.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══ 7. TESTIMONIALS ════════════════════════════════════════════════════ --}}
<section class="lp-section lp-section--dark">
    <div class="lp-max lp-px">
        <span class="section-label lp-reveal">Real Members</span>
        <h2 class="lp-heading lp-reveal">What They're <em style="color:var(--brand-gold);font-style:normal;">Saying</em></h2>
        <p class="lp-sub lp-reveal">Don't just take our word for it.</p>

        <div class="lp-testimonials-grid lp-stagger">

            <div class="lp-testimonial-card">
                <div class="lp-testimonial-card__quote-decor" aria-hidden="true">"</div>
                <div class="lp-testimonial-card__stars" aria-label="5 stars">
                    @for($s = 0; $s < 5; $s++)
                        <svg class="lp-testimonial-star" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="lp-testimonial-card__quote">The coaches here transformed my training completely. Best investment I've made for my health.</p>
                <div class="lp-testimonial-card__footer">
                    <div class="lp-testimonial-card__avatar">M</div>
                    <div>
                        <p class="lp-testimonial-card__name">Maria Santos</p>
                        <p class="lp-testimonial-card__role">Member since 2021</p>
                    </div>
                </div>
            </div>

            <div class="lp-testimonial-card">
                <div class="lp-testimonial-card__quote-decor" aria-hidden="true">"</div>
                <div class="lp-testimonial-card__stars" aria-label="5 stars">
                    @for($s = 0; $s < 5; $s++)
                        <svg class="lp-testimonial-star" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="lp-testimonial-card__quote">Amigos has the best community energy. I look forward to every session — never felt this motivated.</p>
                <div class="lp-testimonial-card__footer">
                    <div class="lp-testimonial-card__avatar">R</div>
                    <div>
                        <p class="lp-testimonial-card__name">Renz Dela Cruz</p>
                        <p class="lp-testimonial-card__role">Member since 2022</p>
                    </div>
                </div>
            </div>

            <div class="lp-testimonial-card">
                <div class="lp-testimonial-card__quote-decor" aria-hidden="true">"</div>
                <div class="lp-testimonial-card__stars" aria-label="5 stars">
                    @for($s = 0; $s < 5; $s++)
                        <svg class="lp-testimonial-star" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="lp-testimonial-card__quote">Clean facilities, expert staff, and flexible plans. Exactly what I needed to stay consistent.</p>
                <div class="lp-testimonial-card__footer">
                    <div class="lp-testimonial-card__avatar">J</div>
                    <div>
                        <p class="lp-testimonial-card__name">Jessa Reyes</p>
                        <p class="lp-testimonial-card__role">Member since 2023</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══ 8. CTA BANNER ══════════════════════════════════════════════════════ --}}
<div class="lp-cta-banner">
    <div class="lp-cta-banner__bg"></div>
    <div class="lp-cta-banner__overlay"></div>
    <div class="lp-cta-banner__content">
        <span class="section-label" style="margin-bottom:20px;">Join The Family</span>
        <h2 class="lp-cta-banner__heading">
            Ready To <span>Transform?</span>
        </h2>
        <p class="lp-cta-banner__sub">Take the first step. Join hundreds of members who chose to change their lives at Amigos.</p>
        @guest
            <a href="{{ route('register') }}" class="btn-gold" style="font-size:15px; padding:14px 36px;">
                Start Today
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        @else
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}" class="btn-gold" style="font-size:15px; padding:14px 36px;">
                Go to My Dashboard →
            </a>
        @endguest
    </div>
</div>

{{-- ═══ CONTACT ═════════════════════════════════════════════════════════════ --}}
<section id="contact" class="lp-section lp-section--surface">
    <div class="lp-max lp-px">
        <span class="section-label lp-reveal">Find Us</span>
        <h2 class="lp-heading lp-reveal">Visit Amigo's Fitness Gym</h2>

        <div class="lp-info-grid lp-stagger" style="margin-top:40px;">
            <div class="lp-info-card">
                <h4>Hours</h4>
                <p>{{ $content['gym_hours'] }}</p>
            </div>
            <div class="lp-info-card">
                <h4>Address</h4>
                <p>{{ $content['gym_address'] }}</p>
            </div>
            <div class="lp-info-card">
                <h4>Phone</h4>
                <p>{{ $content['gym_phone'] }}</p>
            </div>
        </div>

        <div class="lp-reveal">
            @guest
                <a href="{{ route('register') }}" class="btn-gold" style="font-size:14px; padding:12px 28px;">
                    Join Now — Become a Member
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            @else
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}" class="btn-outline" style="font-size:14px; padding:12px 28px;">
                    Go to My Dashboard →
                </a>
            @endguest
        </div>
    </div>
</section>

<script>
(function () {
    'use strict';

    // ── Hero bg parallax trigger ───────────────────────────────────────
    document.getElementById('lpHero').classList.add('loaded');

    // ── Scroll reveal ──────────────────────────────────────────────────
    const ro = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('lp-vis');
                ro.unobserve(e.target);
            }
        });
    }, { threshold: 0.08 });
    document.querySelectorAll('.lp-reveal, .lp-stagger').forEach(el => ro.observe(el));

    // ── Animated counters ──────────────────────────────────────────────
    const counters = document.querySelectorAll('.lp-counter');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting || entry.target.dataset.done) return;
            entry.target.dataset.done = '1';
            const target  = parseInt(entry.target.dataset.target, 10);
            const suffix  = entry.target.dataset.suffix || '';
            const duration = 1800;
            const fps     = 60;
            const steps   = Math.round(duration / (1000 / fps));
            let current   = 0;
            const increment = target / steps;
            const tick = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(tick);
                }
                entry.target.textContent = Math.floor(current) + suffix;
            }, 1000 / fps);
        });
    }, { threshold: 0.4 });
    counters.forEach(el => counterObserver.observe(el));

})();
</script>

@endsection
