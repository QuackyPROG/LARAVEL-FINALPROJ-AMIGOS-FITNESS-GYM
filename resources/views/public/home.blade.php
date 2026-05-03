@extends('layouts.public')

@section('title', 'Welcome')

@section('content')

<style>
    .hm-max   { max-width: 1200px; margin-left: auto; margin-right: auto; }
    .hm-px    { padding-left: 32px; padding-right: 32px; }

    /* Prevent horizontal scroll */
    body { overflow-x: hidden; }
    .hm-hero  { overflow: hidden; }

    /* Custom scrollbar */
    html {
        scrollbar-width: thin;
        scrollbar-color: #fbbf24 #0a0a0a;
    }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #0a0a0a; }
    ::-webkit-scrollbar-thumb {
        background: #fbbf24;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover { background: #f59e0b; }
    .hm-label {
        display: inline-block;
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: #fbbf24 !important;
        border: 1px solid rgba(251,191,36,0.3);
        padding: 4px 12px; border-radius: 100px;
        margin-bottom: 18px;
    }
    .hm-heading {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: clamp(38px, 4.5vw, 64px);
        font-weight: 900; text-transform: uppercase;
        line-height: 0.96; letter-spacing: -0.01em;
        color: #fbbf24 !important;
        margin-bottom: 16px;
    }
    .hm-sub {
        font-size: 16px; color: #666; line-height: 1.7;
        max-width: 580px;
    }

    /* Scroll reveal */
    .hm-reveal {
        opacity: 0; transform: translateY(22px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    .hm-reveal.hm-visible { opacity: 1; transform: translateY(0); }
    .hm-reveal-stagger > * {
        opacity: 0; transform: translateY(18px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
    .hm-reveal-stagger.hm-visible > *:nth-child(1) { opacity:1; transform:none; transition-delay:0.05s; }
    .hm-reveal-stagger.hm-visible > *:nth-child(2) { opacity:1; transform:none; transition-delay:0.15s; }
    .hm-reveal-stagger.hm-visible > *:nth-child(3) { opacity:1; transform:none; transition-delay:0.25s; }
    .hm-reveal-stagger.hm-visible > *:nth-child(4) { opacity:1; transform:none; transition-delay:0.35s; }
    .hm-hero {
        min-height: calc(100vh - 102px);
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
        gap: 56px;
        padding: 80px 32px;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
    }
    .hm-hero::before {
        content: '';
        position: absolute; top: -60px; right: 0;
        width: 500px; height: 500px;
        background: radial-gradient(ellipse at center, rgba(251,191,36,0.07) 0%, transparent 65%);
        pointer-events: none;
        overflow: hidden;
    }

    .hm-hero__content { position: relative; z-index: 1; }

    .hm-hero__heading {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: clamp(56px, 6.5vw, 96px);
        font-weight: 900; text-transform: uppercase;
        line-height: 0.94; letter-spacing: -0.015em;
        color: #fff !important;
        margin-bottom: 22px;
    }
    .hm-hero__heading em {
        font-style: normal;
        color: #fbbf24 !important;
    }

    .hm-hero__sub {
        font-size: 17px; color: #666; line-height: 1.65;
        max-width: 460px; margin-bottom: 32px;
    }
    .hm-hero__badges {
        display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 36px;
    }
    .hm-hero__badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 11px; font-weight: 600;
        color: #666 !important;
        border: 1px solid #1e1e1e; padding: 5px 11px; border-radius: 100px;
        letter-spacing: 0.04em; text-transform: uppercase;
    }
    .hm-hero__badge-dot {
        width: 6px; height: 6px; border-radius: 50%; background: #fbbf24; flex-shrink: 0;
    }
    .hm-hero__cta { display: flex; gap: 10px; flex-wrap: wrap; }

    .hm-btn-gold {
        display: inline-flex; align-items: center; gap: 7px;
        background: #fbbf24; color: #000;
        font-size: 14px; font-weight: 700;
        padding: 12px 24px; border-radius: 7px; border: none;
        text-decoration: none; letter-spacing: 0.02em;
        transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
    }
    .hm-btn-gold:hover {
        background: #f59e0b; color: #000;
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(251,191,36,0.25);
    }
    .hm-btn-gold:active { transform: none; }

    .hm-btn-outline {
        display: inline-flex; align-items: center; gap: 7px;
        background: transparent; color: #fff;
        font-size: 14px; font-weight: 600;
        padding: 12px 24px; border-radius: 7px;
        border: 1px solid #2a2a2a; text-decoration: none;
        transition: border-color 0.2s, background 0.2s;
    }
    .hm-btn-outline:hover { border-color: #444; background: #111; color: #fff; }
    .hm-hero__visual { position: relative; z-index: 1; }

    .hm-hero__img-wrap {
        width: 100%; aspect-ratio: 4/5;
        background: #0f0f0f; border-radius: 20px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 0 0 1px rgba(251,191,36,0.12), 0 32px 80px rgba(0,0,0,0.7);
    }
    .hm-hero__img-wrap::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(
            to bottom,
            transparent 50%,
            rgba(0,0,0,0.55) 100%
        );
        pointer-events: none;
        z-index: 1;
    }
    .hm-hero__img-wrap img {
        width: 100%; height: 100%; object-fit: cover;
        object-position: center top;
        transition: transform 0.65s ease;
        display: block;
    }
    .hm-hero__img-wrap:hover img { transform: scale(1.04); }

    /* Gold accent bar on the left edge of the card */
    .hm-hero__img-wrap::before {
        content: '';
        position: absolute; left: 0; top: 15%; bottom: 15%;
        width: 3px; background: #fbbf24;
        border-radius: 0 3px 3px 0;
        z-index: 2;
        box-shadow: 0 0 16px rgba(251,191,36,0.5);
    }

    .hm-hero__thumbs { display: flex; gap: 8px; margin-top: 12px; }
    .hm-hero__thumb {
        flex: 1; aspect-ratio: 1; background: #0f0f0f;
        border-radius: 10px; overflow: hidden;
        border: 2px solid transparent; cursor: pointer;
        transition: border-color 0.2s;
    }
    .hm-hero__thumb.active { border-color: #fbbf24; }
    .hm-hero__thumb img { width: 100%; height: 100%; object-fit: cover; }
    .hm-marquee {
        background: #0a0a0a; border-top: 1px solid #141414;
        border-bottom: 1px solid #141414;
        padding: 16px 0; overflow: hidden;
    }
    .hm-marquee__track {
        display: flex; gap: 52px; align-items: center;
        animation: hmMarquee 24s linear infinite; width: max-content;
    }
    .hm-marquee:hover .hm-marquee__track { animation-play-state: paused; }
    .hm-marquee__item {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.1em; color: #2e2e2e; white-space: nowrap;
        display: flex; align-items: center; gap: 10px;
    }
    .hm-marquee__item::before {
        content: '✦'; color: #fbbf24; font-size: 9px;
    }
    @keyframes hmMarquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    .hm-section { padding: 100px 32px; }
    .hm-section--dark { background: #080808; }
    .hm-section--surface { background: #0a0a0a; }

    .hm-plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 340px));
        justify-content: center;
        gap: 16px; margin-top: 52px;
    }
    .hm-plan-card {
        background: #0b0b0b; border: 1px solid #1a1a1a;
        border-radius: 16px; padding: 32px 26px;
        position: relative;
        transition: border-color 0.25s, transform 0.25s;
        display: flex; flex-direction: column;
    }
    .hm-plan-card:hover {
        border-color: #333;
        transform: translateY(-3px);
    }
    .hm-plan-card:nth-child(2) {
        border-color: #fbbf24;
        background: #0e0e0e;
    }
    .hm-plan-card:nth-child(2)::before {
        content: 'Most Popular';
        position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
        background: #fbbf24; color: #000;
        font-size: 10px; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.1em; padding: 3px 14px; border-radius: 100px;
    }

    .hm-plan__name {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.1em; color: #555 !important; margin-bottom: 10px;
    }
    .hm-plan__price {
        font-family: 'Barlow Condensed', system-ui, sans-serif;
        font-size: 52px; font-weight: 900; line-height: 1;
        color: #fff !important; margin-bottom: 2px;
    }
    .hm-plan__price .hm-plan__currency {
        font-size: 20px; font-weight: 400; color: #555 !important;
        vertical-align: super; font-family: inherit;
    }
    .hm-plan__period { font-size: 12px; color: #444; margin-bottom: 24px; }

    .hm-plan__benefits {
        list-style: none; display: flex; flex-direction: column;
        gap: 10px; margin-bottom: 28px; flex: 1;
    }
    .hm-plan__benefits li {
        display: flex; align-items: flex-start; gap: 8px;
        font-size: 14px; color: #666;
    }
    .hm-plan__benefits li::before {
        content: '✓'; color: #fbbf24; font-weight: 700;
        font-size: 12px; flex-shrink: 0; margin-top: 2px;
    }

    .hm-plan-card:nth-child(2) .hm-plan-btn { background: #fbbf24; color: #000; border: none; }
    .hm-plan-card:nth-child(2) .hm-plan-btn:hover { background: #f59e0b; }

    .hm-plan-btn {
        display: block; width: 100%; text-align: center;
        font-size: 13px; font-weight: 700; letter-spacing: 0.03em;
        padding: 11px 0; border-radius: 7px;
        border: 1px solid #2a2a2a; color: #fff;
        text-decoration: none; background: transparent;
        transition: border-color 0.2s, background 0.2s, color 0.2s;
    }
    .hm-plan-btn:hover { border-color: #fbbf24; color: #fbbf24; }

    .hm-coaches-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 320px));
        justify-content: center;
        gap: 16px; margin-top: 52px;
    }
    .hm-coach-card {
        background: #0b0b0b; border: 1px solid #1a1a1a;
        border-radius: 14px; padding: 26px 22px;
        transition: border-color 0.25s, transform 0.25s;
    }
    .hm-coach-card:hover { border-color: #2a2a2a; transform: translateY(-2px); }

    .hm-coach-avatar {
        width: 56px; height: 56px; border-radius: 50%;
        background: #1a1a1a; border: 2px solid #222;
        overflow: hidden; margin-bottom: 14px;
        display: flex; align-items: center; justify-content: center;
    }
    .hm-coach-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .hm-coach-avatar span {
        font-size: 22px; font-weight: 700; color: #555 !important;
        font-family: 'Barlow Condensed', system-ui, sans-serif;
    }

    .hm-coach-name {
        font-size: 16px; font-weight: 700; color: #fff !important;
        margin-bottom: 8px;
    }

    .hm-coach-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 10px; }
    .hm-coach-tag {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.06em; color: #fbbf24 !important;
        background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.2);
        padding: 3px 9px; border-radius: 100px;
    }

    .hm-coach-bio { font-size: 13px; color: #555; line-height: 1.6; }
    .hm-info-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 12px; margin-bottom: 40px;
    }
    .hm-info-card {
        background: #0b0b0b; border: 1px solid #1a1a1a;
        border-radius: 12px; padding: 22px 20px;
    }
    .hm-info-card h4 {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: #fbbf24 !important; margin-bottom: 8px;
    }
    .hm-info-card p { font-size: 14px; color: #888; line-height: 1.55; }

    @media (max-width: 1024px) {
        .hm-hero {
            grid-template-columns: 1fr;
            min-height: auto; gap: 40px; padding: 60px 24px;
        }
        .hm-hero::before { display: none; }
        .hm-hero__visual { order: -1; }
        .hm-hero__img-wrap { aspect-ratio: 16/9; }
        .hm-hero__thumbs { display: none; }
        .hm-plans-grid { grid-template-columns: 1fr; max-width: 420px; margin-left: auto; margin-right: auto; }
        .hm-coaches-grid { grid-template-columns: 1fr 1fr; }
        .hm-info-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .hm-section { padding: 64px 20px; }
        .hm-hero { padding: 48px 20px; }
        .hm-hero__heading { font-size: 52px; }
        .hm-coaches-grid { grid-template-columns: 1fr; }
        .hm-plans-grid { grid-template-columns: 1fr; max-width: 100%; }
    }
</style>

<section class="hm-hero">

    <div class="hm-hero__content">

        <span class="hm-label">Amigos Fitness Gym</span>

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
                <a href="{{ route('register') }}" class="hm-btn-gold">
                    Become a Member
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="/#plans" class="hm-btn-outline">View Plans</a>
            @else
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}" class="hm-btn-gold">
                    Go to My Dashboard →
                </a>
            @endguest
        </div>

    </div>

    <div class="hm-hero__visual">
        <div class="hm-hero__img-wrap" id="hmHeroMain">
            @if($content['hero_image'])
                <img
                    src="{{ asset('storage/' . $content['hero_image']) }}"
                    alt="Amigos Fitness Gym"
                    loading="eager"
                >
            @else
                <img
                    src="{{ asset('images/hero-gym.jpg') }}"
                    alt="Train at Amigo's Fitness Gym"
                    loading="eager"
                >
            @endif
        </div>

        @if($content['hero_image'])
        <div class="hm-hero__thumbs">
            <div class="hm-hero__thumb active" data-src="{{ asset('storage/' . $content['hero_image']) }}">
                <img src="{{ asset('storage/' . $content['hero_image']) }}" alt="">
            </div>
        </div>
        @endif
    </div>

</section>

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

<section id="plans" class="hm-section hm-section--surface">
    <div class="hm-max hm-px">

        <span class="hm-label hm-reveal">Membership</span>
        <h2 class="hm-heading hm-reveal">Choose Your Plan</h2>
        <p class="hm-sub hm-reveal">Flexible options designed for every training goal and schedule.</p>

        <div class="hm-plans-grid hm-reveal-stagger">
            @foreach($plans as $plan)
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
            @endforeach
        </div>

    </div>
</section>

@if($coaches->isNotEmpty())
<section id="coaches" class="hm-section hm-section--dark">
    <div class="hm-max hm-px">

        <span class="hm-label hm-reveal">Our Team</span>
        <h2 class="hm-heading hm-reveal">Meet Your Coaches</h2>
        <p class="hm-sub hm-reveal">World-class coaches dedicated to helping you reach your peak performance.</p>

        <div class="hm-coaches-grid hm-reveal-stagger">
            @foreach($coaches as $coach)
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
            @endforeach
        </div>

    </div>
</section>
@endif

<section id="contact" class="hm-section hm-section--surface">
    <div class="hm-max hm-px">

        <span class="hm-label hm-reveal">Find Us</span>
        <h2 class="hm-heading hm-reveal">Visit Amigo's Fitness Gym</h2>

        <div class="hm-info-grid hm-reveal-stagger" style="margin-top:44px;">
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
                <a href="{{ route('register') }}" class="hm-btn-gold" style="font-size:15px; padding:13px 28px;">
                    Join Now — Become a Member
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            @else
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}" class="hm-btn-outline" style="font-size:15px; padding:13px 28px;">
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
            if (e.isIntersecting) {
                e.target.classList.add('hm-visible');
                ro.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.hm-reveal, .hm-reveal-stagger').forEach(el => ro.observe(el));

    const heroMain = document.getElementById('hmHeroMain');
    if (heroMain) {
        document.querySelectorAll('.hm-hero__thumb').forEach(thumb => {
            thumb.addEventListener('click', () => {
                document.querySelectorAll('.hm-hero__thumb').forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
                const img = heroMain.querySelector('img');
                if (img) img.src = thumb.dataset.src;
            });
        });
    }

})();
</script>

@endsection