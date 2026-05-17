<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — @yield('title', 'Welcome')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
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

        .pub-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            height: 90px;
            display: flex;
            align-items: center;
            padding: 0 32px;
            background: rgba(8, 8, 8, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(251, 191, 36, 0.12);
            transition: background 0.25s ease, box-shadow 0.25s ease;
        }
        .pub-nav.scrolled {
            background: rgba(5, 5, 5, 0.98);
            box-shadow: 0 2px 32px rgba(0,0,0,0.8), 0 1px 0 rgba(251,191,36,0.08);
        }
        .pub-nav__inner {
            max-width: 1200px; width: 100%; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between; gap: 32px;
        }

        .pub-logo {
            display: flex; align-items: center; gap: 9px;
            text-decoration: none; flex-shrink: 0;
        }
        .pub-logo__img {
            height: 72px;
            width: auto;
            display: block;
            filter: drop-shadow(0 0 8px rgba(251,191,36,0.18));
            transition: filter 0.25s, transform 0.25s;
        }
        .pub-logo:hover .pub-logo__img {
            filter: drop-shadow(0 0 14px rgba(251,191,36,0.45));
            transform: scale(1.04);
        }
        .pub-logo--footer .pub-logo__img {
            height: 100px;
        }

        .pub-nav__links {
            display: flex; align-items: center; gap: 36px; list-style: none;
        }
        .pub-nav__links a {
            font-size: 11px; font-weight: 600; color: #666;
            letter-spacing: 0.1em; text-transform: uppercase;
            text-decoration: none;
            position: relative;
            transition: color 0.2s;
        }
        .pub-nav__links a::after {
            content: ''; position: absolute;
            bottom: -4px; left: 0; right: 0; height: 1.5px;
            background: #fbbf24; transform: scaleX(0);
            transform-origin: left; transition: transform 0.25s;
        }
        .pub-nav__links a:hover { color: #fbbf24; }
        .pub-nav__links a:hover::after { transform: scaleX(1); }

        .pub-nav__actions { display: flex; align-items: center; gap: 8px; }

        .pub-btn-ghost {
            font-size: 11px; font-weight: 600;
            color: #888; padding: 7px 16px;
            border-radius: 6px; border: 1px solid #2a2a2a;
            text-decoration: none; background: transparent;
            letter-spacing: 0.06em; text-transform: uppercase;
            transition: color 0.2s, border-color 0.2s, background 0.2s;
        }
        .pub-btn-ghost:hover { color: #fbbf24; border-color: rgba(251,191,36,0.3); background: rgba(251,191,36,0.05); }

        .pub-btn-primary {
            font-size: 13px; font-weight: 700;
            color: #000; padding: 8px 18px;
            border-radius: 6px; border: none;
            background: #fbbf24; text-decoration: none;
            letter-spacing: 0.02em;
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .pub-btn-primary:hover {
            background: #f59e0b;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(251,191,36,0.3);
            color: #000;
        }
        .pub-btn-primary:active { transform: translateY(0); }

        .pub-hamburger {
            display: none; flex-direction: column; gap: 5px;
            background: none; border: none; padding: 6px; cursor: pointer;
        }
        .pub-hamburger span {
            display: block; width: 22px; height: 2px;
            background: #fff; border-radius: 2px;
            transition: transform 0.2s, opacity 0.2s;
        }

        .pub-mobile-menu {
            display: none; position: fixed; inset: 0;
            z-index: 300; background: #000;
            flex-direction: column; padding: 36px 28px; gap: 28px;
        }
        .pub-mobile-menu.open { display: flex; }
        .pub-mobile-menu__close {
            align-self: flex-end; background: none; border: none;
            color: #fff; font-size: 26px; line-height: 1; cursor: pointer;
        }
        .pub-mobile-menu nav { display: flex; flex-direction: column; gap: 20px; }
        .pub-mobile-menu nav a {
            font-family: 'Barlow Condensed', system-ui, sans-serif;
            font-size: 36px; font-weight: 800; text-transform: uppercase;
            color: #fff; text-decoration: none; letter-spacing: 0.01em;
            transition: color 0.2s;
        }
        .pub-mobile-menu nav a:hover { color: #fbbf24; }

        .pub-main {
            min-height: 100vh;
            background: linear-gradient(160deg, #000000 0%, #0d0d0d 30%, #141414 60%, #1a1a1a 100%);
        }

        .pub-footer {
            background: #0a0a0a;
            border-top: 1px solid #1a1a1a;
            padding: 64px 32px 32px;
        }
        .pub-footer__inner { max-width: 1200px; margin: 0 auto; }

        .pub-footer__top {
            display: grid;
            grid-template-columns: 1.6fr repeat(2, 1fr) 1.4fr;
            gap: 56px;
            padding-bottom: 48px;
            border-bottom: 1px solid #1a1a1a;
            margin-bottom: 32px;
        }

        .pub-footer__brand p {
            font-size: 13px; color: #555; line-height: 1.7;
            margin-top: 14px; max-width: 260px;
        }

        .pub-footer__col h4 {
            font-family: 'Barlow Condensed', system-ui, sans-serif;
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.12em; color: #555 !important;
            margin-bottom: 18px;
        }
        .pub-footer__col ul { list-style: none; display: flex; flex-direction: column; gap: 11px; }
        .pub-footer__col a {
            font-size: 13px; color: #444; text-decoration: none;
            transition: color 0.2s;
        }
        .pub-footer__col a:hover { color: #fff; }

        .pub-footer__social-link {
            display: flex !important; align-items: center; gap: 9px;
        }
        .pub-footer__social-link svg { flex-shrink: 0; opacity: 0.6; transition: opacity 0.2s; }
        .pub-footer__social-link:hover svg { opacity: 1; }
        .pub-footer__social-link:hover { color: #fbbf24 !important; }

        .pub-footer__newsletter h4 {
            font-family: 'Barlow Condensed', system-ui, sans-serif;
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.12em; color: #555 !important; margin-bottom: 8px;
        }
        .pub-footer__newsletter p { font-size: 12px; color: #444; margin-bottom: 14px; }
        .pub-footer__newsletter-form { display: flex; gap: 6px; }
        .pub-footer__newsletter-form input {
            flex: 1; background: #111; border: 1px solid #222;
            color: #fff; font-size: 13px; padding: 9px 12px;
            border-radius: 6px; outline: none;
            transition: border-color 0.2s;
        }
        .pub-footer__newsletter-form input:focus { border-color: #fbbf24; }
        .pub-footer__newsletter-form input::placeholder { color: #444; }
        .pub-footer__newsletter-form button {
            background: #fbbf24; color: #000;
            border: none; border-radius: 6px;
            font-size: 12px; font-weight: 700; padding: 9px 16px;
            cursor: pointer; white-space: nowrap;
            transition: background 0.2s;
        }
        .pub-footer__newsletter-form button:hover { background: #f59e0b; }

        .pub-footer__bottom {
            display: flex; align-items: center; justify-content: space-between;
            gap: 16px; flex-wrap: wrap;
        }
        .pub-footer__copy { font-size: 12px; color: #333; }
        .pub-footer__copy a { color: #333; text-decoration: none; transition: color 0.2s; }
        .pub-footer__copy a:hover { color: #fff; }

        .pub-footer__socials { display: flex; gap: 10px; }
        .pub-footer__socials a {
            width: 34px; height: 34px; border: 1px solid #1f1f1f;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: #444; font-size: 13px; text-decoration: none;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }
        .pub-footer__socials a:hover {
            border-color: #fbbf24; color: #fbbf24;
            background: rgba(251,191,36,0.06);
        }

        @media (max-width: 1024px) {
            .pub-footer__top { grid-template-columns: 1fr 1fr; gap: 36px; }
        }
        @media (max-width: 768px) {
            .pub-nav { padding: 0 20px; top: 0; }
            .pub-nav__links, .pub-nav__actions .pub-btn-ghost { display: none; }
            .pub-hamburger { display: flex; }
            .pub-footer { padding: 48px 20px 28px; }
            .pub-footer__top { grid-template-columns: 1fr; gap: 28px; }
            .pub-footer__bottom { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body class="bg-dark-page text-dark antialiased">

    <header class="pub-nav" id="pubNav">
        <div class="pub-nav__inner">

            <a href="{{ url('/') }}" class="pub-logo">
                <img src="{{ asset('images/amigos1.png') }}" alt="Amigo's Fitness Gym" class="pub-logo__img">
            </a>

            <ul class="pub-nav__links">
                <li><a href="/#plans">Plans</a></li>
                <li><a href="/#coaches">Coaches</a></li>
                <li><a href="/#contact">Contact</a></li>
            </ul>

            <div class="pub-nav__actions">
                @guest
                    <a href="{{ route('login') }}" class="pub-btn-ghost">Member Login</a>
                    <a href="{{ route('register') }}" class="pub-btn-primary">
                        Become a Member
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @else
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="pub-btn-ghost">Admin Panel →</a>
                    @else
                        <a href="{{ route('portal.dashboard') }}" class="pub-btn-ghost">My Dashboard →</a>
                    @endif
                @endguest
            </div>

            <button class="pub-hamburger" id="pubMenuToggle" aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <div class="pub-mobile-menu" id="pubMobileMenu">
        <button class="pub-mobile-menu__close" id="pubMenuClose" aria-label="Close">✕</button>
        <nav>
            <a href="/#plans"    onclick="closePubMenu()">Plans</a>
            <a href="/#coaches"  onclick="closePubMenu()">Coaches</a>
            <a href="/#contact"  onclick="closePubMenu()">Contact</a>
            @guest
                <a href="{{ route('login') }}"    onclick="closePubMenu()">Log In</a>
                <a href="{{ route('register') }}" onclick="closePubMenu()">Sign Up</a>
            @else
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
                @else
                    <a href="{{ route('portal.dashboard') }}">My Dashboard</a>
                @endif
            @endguest
        </nav>
        @guest
            <a href="{{ route('register') }}" class="pub-btn-primary" style="align-self:flex-start; font-size:15px; padding:13px 26px;">
                Become a Member
            </a>
        @endguest
    </div>

    <main class="pub-main">
        @yield('content')
    </main>

    <footer class="pub-footer">
        <div class="pub-footer__inner">

            <div class="pub-footer__top">

                <div class="pub-footer__brand">
                    <a href="{{ url('/') }}" class="pub-logo pub-logo--footer">
                        <img src="{{ asset('images/amigos1.png') }}" alt="Amigo's Fitness Gym" class="pub-logo__img">
                    </a>
                    <p>Where champions are built. Come for the equipment, stay for the community.</p>
                </div>

                <div class="pub-footer__col">
                    <h4>Gym</h4>
                    <ul>
                        <li><a href="/#plans">Membership Plans</a></li>
                        <li><a href="/#coaches">Our Coaches</a></li>
                        <li><a href="/#contact">Visit Us</a></li>
                        @guest
                            <li><a href="{{ route('register') }}">Join Now</a></li>
                            <li><a href="{{ route('login') }}">Member Login</a></li>
                        @else
                            <li>
                                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}">
                                    My Dashboard →
                                </a>
                            </li>
                        @endguest
                    </ul>
                </div>

                <div class="pub-footer__col">
                    <h4>Follow Us</h4>
                    <ul>
                        <li>
                            <a href="#" rel="noopener" class="pub-footer__social-link">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                                Facebook
                            </a>
                        </li>
                        <li>
                            <a href="#" rel="noopener" class="pub-footer__social-link">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                                Instagram
                            </a>
                        </li>
                        <li>
                            <a href="#" rel="noopener" class="pub-footer__social-link">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z"/></svg>
                                TikTok
                            </a>
                        </li>
                        <li>
                            <a href="#" rel="noopener" class="pub-footer__social-link">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#000"/></svg>
                                YouTube
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="pub-footer__newsletter pub-footer__col">
                    <h4>Stay in the Loop</h4>
                    <p>New classes, promos, and tips — zero spam.</p>
                    <form class="pub-footer__newsletter-form" method="POST" action="#">
                        @csrf
                        <input type="email" name="email" placeholder="your@email.com" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>

            </div>

            <div class="pub-footer__bottom">
                <p class="pub-footer__copy">
                    &copy; {{ date('Y') }} AmigosFitnessGym. All rights reserved.
                    &nbsp;·&nbsp; <a href="#">Privacy Policy</a>
                    &nbsp;·&nbsp; <a href="#">Terms of Service</a>
                </p>
                <div class="pub-footer__socials" aria-label="Social media">
                    <a href="#" aria-label="Facebook">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="#" aria-label="Instagram">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <a href="#" aria-label="TikTok">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z"/></svg>
                    </a>
                    <a href="#" aria-label="YouTube">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#0a0a0a"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </footer>

    @fluxScripts
    @livewireScripts

    <script>
    (function () {
        'use strict';

        const nav = document.getElementById('pubNav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });

        document.getElementById('pubMenuToggle').addEventListener('click', () => {
            document.getElementById('pubMobileMenu').classList.add('open');
            document.body.style.overflow = 'hidden';
        });

        document.getElementById('pubMenuClose').addEventListener('click', closePubMenu);

        window.closePubMenu = function () {
            document.getElementById('pubMobileMenu').classList.remove('open');
            document.body.style.overflow = '';
        };

        document.getElementById('pubMobileMenu').addEventListener('click', function (e) {
            if (e.target === this) closePubMenu();
        });
    })();
    </script>

</body>
</html>