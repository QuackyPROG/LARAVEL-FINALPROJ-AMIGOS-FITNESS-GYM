@extends('layouts.public')

@section('title', 'Welcome')

@section('content')

<style>
    body { overflow-x: hidden; }
    .hm-max { max-width: 1200px; margin-left: auto; margin-right: auto; }
    .hm-px  { padding-left: 28px; padding-right: 28px; }

    /* ── Scroll reveal ──────────────────────────────────────── */
    .hm-reveal {
        opacity: 0; transform: translateY(18px);
        transition: opacity 0.55s ease, transform 0.55s ease;
    }
    .hm-reveal.hm-visible { opacity: 1; transform: none; }
    .hm-reveal-stagger > * {
        opacity: 0; transform: translateY(14px);
        transition: opacity 0.45s ease, transform 0.45s ease;
    }
    .hm-reveal-stagger.hm-visible > *:nth-child(1) { opacity:1; transform:none; transition-delay:0.04s; }
    .hm-reveal-stagger.hm-visible > *:nth-child(2) { opacity:1; transform:none; transition-delay:0.12s; }
    .hm-reveal-stagger.hm-visible > *:nth-child(3) { opacity:1; transform:none; transition-delay:0.20s; }
    .hm-reveal-stagger.hm-visible > *:nth-child(4) { opacity:1; transform:none; transition-delay:0.28s; }
    /* Plan + coach grids skip animation — render immediately */
    .hm-plans-grid.hm-reveal-stagger > *,
    .hm-coaches-grid.hm-reveal-stagger > * { opacity:1; transform:none; }

    /* ── Hero ───────────────────────────────────────────────── */
    .hm-hero {
        min-height: calc(100vh - 72px);
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
        gap: 56px;
        padding: 80px 28px;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
    }
    .hm-hero::before {
        content: '';
        position: absolute; top: -40px; right: 0;
        width: 480px; height: 480px;
        background: radial-gradient(ellipse at center, rgba(232,160,32,0.05) 0%, transparent 68%);
        pointer-events: none;
    }
    .hm-hero__content { position: relative; z-index: 1; }
    .hm-hero__heading {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: clamp(52px, 6vw, 88px);
        font-weight: 900; text-transform: uppercase;
        line-height: 0.94; letter-spacing: -0.015em;
        color: #f0f0f0;
        margin-bottom: 20px;
    }
    .hm-hero__heading em {
        font-style: normal;
        color: var(--brand-gold);
    }
    .hm-hero__sub {
        font-size: 16px; color: var(--text-secondary); line-height: 1.65;
        max-width: 440px; margin-bottom: 28px;
    }
    .hm-hero__badges {
        display: flex; flex-wrap: wrap; gap: 7px; margin-bottom: 32px;
    }
    .hm-hero__badge {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 10px; font-weight: 600;
        color: var(--text-muted);
        border: 1px solid var(--surface-3); padding: 5px 12px; border-radius: 100px;
        letter-spacing: 0.05em; text-transform: uppercase;
    }
    .hm-hero__badge-dot {
        width: 5px; height: 5px; border-radius: 50%;
        background: var(--brand-gold); flex-shrink: 0;
    }
    .hm-hero__cta { display: flex; gap: 10px; flex-wrap: wrap; }

    /* ── Hero visual ────────────────────────────────────────── */
    .hm-hero__visual { position: relative; z-index: 1; }
    .hm-hero__img-wrap {
        width: 100%; aspect-ratio: 4/5;
        background: var(--surface-2); border-radius: 18px;
        overflow: hidden; position: relative;
        box-shadow: 0 0 0 1px rgba(232,160,32,0.1), 0 28px 70px rgba(0,0,0,0.65);
    }
    .hm-hero__img-wrap::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(to bottom, transparent 55%, rgba(0,0,0,0.5) 100%);
        pointer-events: none; z-index: 1;
    }
    .hm-hero__img-wrap::before {
        content: '';
        position: absolute; left: 0; top: 18%; bottom: 18%;
        width: 3px; background: var(--brand-gold);
        border-radius: 0 3px 3px 0; z-index: 2;
        box-shadow: 0 0 14px rgba(232,160,32,0.4);
    }
    .hm-hero__img-wrap img {
        width: 100%; height: 100%; object-fit: cover;
        object-position: center top;
        transition: transform 0.6s ease; display: block;
    }
    .hm-hero__img-wrap:hover img { transform: scale(1.03); }


    /* ── Marquee ────────────────────────────────────────────── */
    .hm-marquee {
        background: var(--surface-1);
        border-top: 1px solid rgba(255,255,255,0.05);
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding: 14px 0; overflow: hidden;
    }
    .hm-marquee__track {
        display: flex; gap: 52px; align-items: center;
        animation: hmMarquee 26s linear infinite; width: max-content;
    }
    .hm-marquee:hover .hm-marquee__track { animation-play-state: paused; }
    .hm-marquee__item {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: #282828; white-space: nowrap;
        display: flex; align-items: center; gap: 10px;
    }
    .hm-marquee__item::before { content: '✦'; color: var(--brand-gold); font-size: 8px; }
    @keyframes hmMarquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    /* ── Sections ───────────────────────────────────────────── */
    .hm-section { padding: 96px 28px; }
    .hm-section--dark    { background: #060606; }
    .hm-section--surface { background: #090909; }
    .hm-heading {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: clamp(36px, 4vw, 58px);
        font-weight: 900; text-transform: uppercase;
        line-height: 0.96; letter-spacing: -0.01em;
        color: #ebebeb;
        margin-bottom: 14px;
    }
    .hm-sub {
        font-size: 15px; color: var(--text-secondary); line-height: 1.7;
        max-width: 540px;
    }

    /* ── Plan cards ─────────────────────────────────────────── */
    .hm-plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 320px));
        justify-content: center;
        gap: 14px; margin-top: 48px;
    }
    .hm-plan-card {
        background: #090909; border: 1px solid #181818;
        border-radius: 14px; padding: 28px 24px;
        position: relative; display: flex; flex-direction: column;
        transition: border-color 0.22s, transform 0.22s;
    }
    .hm-plan-card:hover { border-color: #2a2a2a; transform: translateY(-2px); }
    .hm-plan-card:nth-child(2) { border-color: rgba(232,160,32,0.35); background: #0c0c0c; }
    .hm-plan-card:nth-child(2)::before {
        content: 'Most Popular';
        position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
        background: var(--brand-gold); color: #000;
        font-size: 9px; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.1em; padding: 3px 14px; border-radius: 100px;
    }
    .hm-plan__name {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--text-muted); margin-bottom: 10px;
    }
    .hm-plan__price {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: 48px; font-weight: 900; line-height: 1;
        color: #f0f0f0; margin-bottom: 2px;
    }
    .hm-plan__price .hm-plan__currency {
        font-size: 18px; font-weight: 400; color: var(--text-muted);
        vertical-align: super; font-family: inherit;
    }
    .hm-plan__period { font-size: 12px; color: #3a3a3a; margin-bottom: 22px; }
    .hm-plan__benefits {
        list-style: none; display: flex; flex-direction: column;
        gap: 9px; margin-bottom: 24px; flex: 1;
    }
    .hm-plan__benefits li {
        display: flex; align-items: flex-start; gap: 8px;
        font-size: 13px; color: #585858;
    }
    .hm-plan__benefits li::before {
        content: '✓'; color: var(--brand-gold); font-weight: 700;
        font-size: 11px; flex-shrink: 0; margin-top: 2px;
    }
    .hm-plan-btn {
        display: block; width: 100%; text-align: center;
        font-size: 12px; font-weight: 700; letter-spacing: 0.03em;
        padding: 10px 0; border-radius: 7px;
        border: 1px solid #222; color: #aaa;
        text-decoration: none; background: transparent;
        transition: border-color 0.2s, color 0.2s;
    }
    .hm-plan-btn:hover { border-color: var(--brand-gold-border); color: var(--brand-gold); }
    .hm-plan-card:nth-child(2) .hm-plan-btn {
        background: var(--brand-gold); color: #000; border-color: transparent;
    }
    .hm-plan-card:nth-child(2) .hm-plan-btn:hover { background: var(--brand-gold-hover); }

    .hm-empty-card {
        grid-column: 1 / -1; max-width: 460px; width: 100%; margin: 0 auto;
        background: rgba(232,160,32,0.04); border: 1px dashed rgba(232,160,32,0.22);
        border-radius: 14px; padding: 26px 22px; text-align: center;
    }
    .hm-empty-card h3 { color: #c0c0c0; font-size: 15px; font-weight: 700; margin-bottom: 6px; }
    .hm-empty-card p  { color: var(--text-muted); font-size: 13px; line-height: 1.6; }

    /* ── Coach cards ────────────────────────────────────────── */
    .hm-coaches-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 300px));
        justify-content: center;
        gap: 14px; margin-top: 48px;
    }
    .hm-coach-card {
        background: #090909; border: 1px solid #181818;
        border-radius: 12px; padding: 22px 20px;
        transition: border-color 0.22s, transform 0.22s;
    }
    .hm-coach-card:hover { border-color: #252525; transform: translateY(-2px); }
    .hm-coach-avatar {
        width: 52px; height: 52px; border-radius: 50%;
        background: var(--surface-3); border: 1.5px solid #222;
        overflow: hidden; margin-bottom: 13px;
        display: flex; align-items: center; justify-content: center;
    }
    .hm-coach-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .hm-coach-avatar span {
        font-size: 20px; font-weight: 700; color: var(--text-muted);
        font-family: 'Barlow Condensed', system-ui, sans-serif;
    }
    .hm-coach-name { font-size: 15px; font-weight: 700; color: #e0e0e0; margin-bottom: 8px; }
    .hm-coach-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 9px; }
    .hm-coach-tag {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.07em; color: var(--brand-gold);
        background: var(--brand-gold-dim); border: 1px solid var(--brand-gold-border);
        padding: 3px 8px; border-radius: 100px;
    }
    .hm-coach-bio { font-size: 12px; color: var(--text-muted); line-height: 1.6; }

    /* ── Contact info grid ──────────────────────────────────── */
    .hm-info-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 10px; margin-bottom: 40px;
    }
    .hm-info-card {
        background: #090909; border: 1px solid #181818;
        border-radius: 11px; padding: 20px 18px;
    }
    .hm-info-card h4 {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.14em; color: var(--brand-gold); margin-bottom: 8px;
    }
    .hm-info-card p { font-size: 13px; color: var(--text-secondary); line-height: 1.55; }

    /* ── Responsive ─────────────────────────────────────────── */
    @media (max-width: 1024px) {
        .hm-hero { grid-template-columns: 1fr; min-height: auto; gap: 36px; padding: 56px 22px; }
        .hm-hero::before { display: none; }
        .hm-hero__visual { order: -1; }
        .hm-hero__img-wrap { aspect-ratio: 16/9; }
        .hm-hero__thumbs { display: none; }
        .hm-plans-grid   { grid-template-columns: 1fr; max-width: 380px; margin-left: auto; margin-right: auto; }
        .hm-coaches-grid { grid-template-columns: 1fr 1fr; }
        .hm-info-grid    { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .hm-section { padding: 60px 18px; }
        .hm-hero    { padding: 44px 18px; }
        .hm-hero__heading { font-size: 48px; }
        .hm-coaches-grid  { grid-template-columns: 1fr; }
        .hm-plans-grid    { grid-template-columns: 1fr; max-width: 100%; }
    }
</style>

{{-- ═══ HERO ═══════════════════════════════════════════════════════════════ --}}
<section class="hm-hero">

    <div class="hm-hero__content">

        <span class="section-label">Amigos Fitness Gym</span>

        <h1 class="hm-hero__heading">
            {!! nl2br(e($content['hero_title'])) !!}
        </h1>

        <p class="hm-hero__sub">{{ $content['hero_subtitle'] }}</p>

        <div class="hm-hero__badges">
            <span class="hm-hero__badge"><span class="hm-hero__badge-dot"></span>Certified Trainers</span>
            <span class="hm-hero__badge"><span class="hm-hero__badge-dot"></span>No Contracts</span>
        </div>

        <div class="hm-hero__cta">
            @guest
                <a href="{{ route('register') }}" class="btn-gold">
                    Become a Member
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="/#plans" class="btn-outline">View Plans</a>
            @else
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}" class="btn-gold">
                    Go to My Dashboard →
                </a>
            @endguest
        </div>

    </div>

    <div class="hm-hero__visual">
        <div class="hm-hero__img-wrap" id="hmHeroMain">
            @if($content['hero_image'])
                <img src="{{ asset('storage/' . $content['hero_image']) }}" alt="Amigos Fitness Gym" loading="eager">
            @else
                <img src="{{ asset('images/hero-gym.jpg') }}" alt="Train at Amigo's Fitness Gym" loading="eager">
            @endif
        </div>

    </div>

</section>

{{-- ═══ MARQUEE ══════════════════════════════════════════════════════════════ --}}
<div class="hm-marquee" aria-hidden="true">
    <div class="hm-marquee__track">
        <span class="hm-marquee__item">Certified Coaches</span>
        <span class="hm-marquee__item">Premium Equipment</span>
        <span class="hm-marquee__item">Group Classes</span>
        <span class="hm-marquee__item">Personal Training</span>
        <span class="hm-marquee__item">Nutrition Guidance</span>
        <span class="hm-marquee__item">Recovery Zone</span>
        <span class="hm-marquee__item">Certified Coaches</span>
        <span class="hm-marquee__item">Premium Equipment</span>
        <span class="hm-marquee__item">Group Classes</span>
        <span class="hm-marquee__item">Personal Training</span>
        <span class="hm-marquee__item">Nutrition Guidance</span>
        <span class="hm-marquee__item">Recovery Zone</span>
    </div>
</div>

{{-- ═══ PLANS ════════════════════════════════════════════════════════════════ --}}
<section id="plans" class="hm-section hm-section--surface">
    <div class="hm-max hm-px">

        <span class="section-label hm-reveal">Membership</span>
        <h2 class="hm-heading hm-reveal">Choose Your Plan</h2>
        <p class="hm-sub hm-reveal">Flexible options designed for every training goal and schedule.</p>

        <div class="hm-plans-grid hm-reveal-stagger hm-visible">
            @forelse($plans as $plan)
                <div class="hm-plan-card" data-plan-name="{{ $plan->name }}">
                    <p class="hm-plan__name">{{ $plan->name }}</p>
                    <div class="hm-plan__price">
                        <span class="hm-plan__currency">₱</span>{{ number_format($plan->price, 0) }}
                    </div>
                    <p class="hm-plan__period">/ {{ $plan->duration_days }}-day access</p>
                    <ul class="hm-plan__benefits">
                        @foreach(($plan->benefits ?? []) as $benefit)
                            <li>{{ $benefit }}</li>
                        @endforeach
                    </ul>
                    @guest
                        <a href="{{ route('register', ['plan' => $plan->id]) }}" class="hm-plan-btn">Get Started</a>
                    @else
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}" class="hm-plan-btn">
                            Go to Dashboard →
                        </a>
                    @endguest
                </div>
            @empty
                <div class="hm-empty-card">
                    <h3>No membership plans yet</h3>
                    <p>Plan cards will appear here automatically after plans are added in the admin plan manager.</p>
                </div>
            @endforelse
        </div>

    </div>
</section>

{{-- ═══ COACHES ══════════════════════════════════════════════════════════════ --}}
<section id="coaches" class="hm-section hm-section--dark">
    <div class="hm-max hm-px">

        <span class="section-label hm-reveal">Our Team</span>
        <h2 class="hm-heading hm-reveal">Meet Your Coaches</h2>
        <p class="hm-sub hm-reveal">World-class coaches dedicated to helping you reach your peak performance.</p>

        <div class="hm-coaches-grid hm-reveal-stagger hm-visible">
            @forelse($coaches as $coach)
                <div class="hm-coach-card" data-coach-name="{{ $coach->name }}">
                    <div class="hm-coach-avatar">
                        @if($coach->photo)
                            <img src="{{ asset('storage/' . $coach->photo) }}" alt="{{ $coach->name }}">
                        @else
                            <span>{{ strtoupper(substr($coach->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <h3 class="hm-coach-name">{{ $coach->name }}</h3>
                    <div class="hm-coach-tags">
                        @foreach(($coach->specializations ?? []) as $spec)
                            <span class="hm-coach-tag">{{ $spec }}</span>
                        @endforeach
                    </div>
                    <p class="hm-coach-bio">{{ $coach->bio }}</p>
                </div>
            @empty
                <div class="hm-empty-card">
                    <h3>No coaches yet</h3>
                    <p>Coach cards will appear here automatically after coaches are added in the admin coach manager.</p>
                </div>
            @endforelse
        </div>

    </div>
</section>

{{-- ═══ CONTACT ══════════════════════════════════════════════════════════════ --}}
<section id="contact" class="hm-section hm-section--surface">
    <div class="hm-max hm-px">

        <span class="section-label hm-reveal">Find Us</span>
        <h2 class="hm-heading hm-reveal">Visit Amigo's Fitness Gym</h2>

        <div class="hm-info-grid hm-reveal-stagger" style="margin-top:40px;">
            <div class="hm-info-card">
                <h4>Hours</h4>
                <p>{{ $content['gym_hours'] }}</p>
            </div>
            <div class="hm-info-card">
                <h4>Address</h4>
                <p>{{ $content['gym_address'] }}</p>
            </div>
            <div class="hm-info-card">
                <h4>Phone</h4>
                <p>{{ $content['gym_phone'] }}</p>
            </div>
        </div>

        <div class="hm-reveal">
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
    const ro = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('hm-visible'); ro.unobserve(e.target); }
        });
    }, { threshold: 0.08 });

    document.querySelectorAll('.hm-reveal, .hm-reveal-stagger').forEach(el => ro.observe(el));


})();
</script>

@endsection
