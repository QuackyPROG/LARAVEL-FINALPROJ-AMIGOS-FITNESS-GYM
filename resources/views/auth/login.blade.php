<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AmigosFitnessGym') }} — Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            margin: 0;
        }

        /* Prevent autofill from overriding colors */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            transition: background-color 5000s ease-in-out 0s;
            -webkit-text-fill-color: #e0e0e0 !important;
        }

        /* ── Layout ─────────────────────────────────────────── */
        .auth-wrap {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 420px 1fr;
        }

        /* ── Form panel ─────────────────────────────────────── */
        .auth-panel {
            background: #040404;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 56px 48px;
            border-right: 1px solid rgba(255,255,255,0.06);
        }

        .auth-logo {
            display: block;
            width: 80px;
            margin-bottom: 40px;
        }

        .auth-heading {
            font-family: 'Barlow Condensed', system-ui, sans-serif;
            font-size: 36px; font-weight: 900;
            text-transform: uppercase; letter-spacing: -0.01em;
            color: #f0f0f0;
            margin-bottom: 6px;
        }
        .auth-subheading {
            font-size: 13px; color: #555;
            margin-bottom: 36px;
        }

        /* ── Alert banners ──────────────────────────────────── */
        .auth-alert {
            display: flex; align-items: flex-start; gap: 10px;
            font-size: 13px; padding: 12px 14px;
            border-radius: 8px; margin-bottom: 20px;
        }
        .auth-alert--success {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.2);
            color: #6ee7b7;
        }
        .auth-alert--error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #fca5a5;
        }
        .auth-alert svg { flex-shrink: 0; margin-top: 1px; }

        /* ── Form fields ────────────────────────────────────── */
        .auth-form { display: flex; flex-direction: column; gap: 18px; }

        .auth-field { display: flex; flex-direction: column; gap: 6px; }
        .auth-label {
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.1em; color: #555;
        }
        .auth-input-wrap { position: relative; display: flex; align-items: center; }
        .auth-input-icon {
            position: absolute; left: 14px;
            color: #383838; pointer-events: none;
            transition: color 0.2s;
        }
        .auth-input {
            width: 100%;
            background: #0a0a0a;
            border: 1px solid #1e1e1e;
            border-radius: 8px;
            padding: 11px 14px 11px 42px;
            font-size: 14px; color: #e0e0e0;
            font-family: 'Inter', system-ui, sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .auth-input::placeholder { color: #303030; }
        .auth-input:focus {
            border-color: var(--brand-gold-border);
            box-shadow: 0 0 0 3px rgba(232,160,32,0.06);
        }
        .auth-input:focus ~ .auth-input-icon,
        .auth-input-wrap:focus-within .auth-input-icon { color: var(--brand-gold); }

        .auth-input--pr { padding-right: 44px; } /* for toggle button */
        .auth-toggle-vis {
            position: absolute; right: 12px;
            background: none; border: none;
            color: #383838; cursor: pointer; padding: 4px;
            transition: color 0.2s;
        }
        .auth-toggle-vis:hover { color: var(--brand-gold); }

        /* ── Remember / forgot row ──────────────────────────── */
        .auth-meta {
            display: flex; align-items: center; justify-content: space-between;
            margin-top: -4px;
        }
        .auth-remember {
            display: flex; align-items: center; gap: 8px;
            cursor: pointer;
        }
        .auth-remember__box {
            appearance: none; -webkit-appearance: none;
            width: 15px; height: 15px;
            border: 1px solid #2a2a2a; border-radius: 4px;
            background: #0a0a0a;
            cursor: pointer;
            position: relative;
            transition: border-color 0.2s, background 0.2s;
        }
        .auth-remember__box:checked {
            background: var(--brand-gold);
            border-color: var(--brand-gold);
        }
        .auth-remember__box:checked::after {
            content: '✓';
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 9px; font-weight: 800; color: #000;
        }
        .auth-remember__label { font-size: 12px; color: #555; }
        .auth-forgot {
            font-size: 12px; color: var(--brand-gold);
            text-decoration: none;
            transition: color 0.2s;
        }
        .auth-forgot:hover { color: var(--brand-gold-hover); }

        /* ── Submit ─────────────────────────────────────────── */
        .auth-submit {
            width: 100%; margin-top: 6px;
            background: var(--brand-gold); color: #000;
            border: none; border-radius: 8px;
            padding: 13px;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 13px; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            cursor: pointer;
            transition: background 0.18s, transform 0.18s;
        }
        .auth-submit:hover {
            background: var(--brand-gold-hover);
            transform: translateY(-1px);
        }
        .auth-submit:active { transform: none; }

        .auth-footer-text {
            margin-top: 28px; text-align: center;
            font-size: 13px; color: #3a3a3a;
        }
        .auth-footer-text a {
            color: #888; font-weight: 600; text-decoration: none;
            transition: color 0.2s;
        }
        .auth-footer-text a:hover { color: #e0e0e0; }

        /* ── Image panel ────────────────────────────────────── */
        .auth-visual {
            position: relative; overflow: hidden; background: #000;
        }
        .auth-visual__img {
            position: absolute; inset: 0;
            width: 100%; height: 100%; object-fit: cover;
            object-position: center;
            opacity: 0.55;
            animation: authZoom 22s infinite alternate ease-in-out;
        }
        @keyframes authZoom {
            from { transform: scale(1); }
            to   { transform: scale(1.07); }
        }
        .auth-visual__overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to right, rgba(4,4,4,0.35) 0%, transparent 40%),
                        linear-gradient(to top, rgba(0,0,0,0.75) 0%, transparent 45%);
        }
        .auth-visual__copy {
            position: absolute; bottom: 48px; left: 48px; right: 48px; z-index: 2;
        }
        .auth-visual__rule {
            width: 40px; height: 3px;
            background: var(--brand-gold);
            border-radius: 2px; margin-bottom: 18px;
        }
        .auth-visual__headline {
            font-family: 'Barlow Condensed', system-ui, sans-serif;
            font-size: clamp(32px, 3.5vw, 52px);
            font-weight: 900; text-transform: uppercase;
            line-height: 0.96; letter-spacing: -0.01em;
            color: #f8f8f8;
        }
        .auth-visual__headline span { color: var(--brand-gold); }
        .auth-visual__desc {
            margin-top: 14px; font-size: 14px; color: #888;
            max-width: 380px; line-height: 1.6;
        }

        /* ── Responsive ─────────────────────────────────────── */
        @media (max-width: 900px) {
            .auth-wrap { grid-template-columns: 1fr; }
            .auth-visual { display: none; }
            .auth-panel {
                padding: 48px 28px;
                border-right: none;
                min-height: 100vh;
                justify-content: flex-start;
                padding-top: 60px;
            }
        }
    </style>
</head>

<body class="bg-dark-page text-dark antialiased">

    <div class="auth-wrap">

        {{-- ── Form panel ─────────────────────────────────── --}}
        <div class="auth-panel">

            <a href="{{ url('/') }}">
                <img src="{{ asset('images/amigos1.png') }}" alt="Amigos Fitness Gym" class="auth-logo">
            </a>

            <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#444;text-decoration:none;margin-bottom:32px;transition:color 0.2s;" onmouseenter="this.style.color='#888'" onmouseleave="this.style.color='#444'">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to website
            </a>

            <h1 class="auth-heading">Welcome Back</h1>
            <p class="auth-subheading">Sign in to access your member dashboard.</p>

            @if (session('status'))
                <div class="auth-alert auth-alert--success">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="auth-alert auth-alert--error">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="auth-form">
                @csrf

                {{-- Email / Username --}}
                <div class="auth-field">
                    <label class="auth-label" for="login-email">Email or Username</label>
                    <div class="auth-input-wrap">
                        <span class="auth-input-icon">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input id="login-email" type="text" name="email" value="{{ old('email') }}"
                               class="auth-input" placeholder="you@example.com" required autofocus>
                    </div>
                </div>

                {{-- Password --}}
                <div class="auth-field">
                    <label class="auth-label" for="login-password">Password</label>
                    <div class="auth-input-wrap">
                        <span class="auth-input-icon">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input id="login-password" type="password" name="password"
                               class="auth-input auth-input--pr" placeholder="••••••••" required>
                        <button type="button" id="togglePw" class="auth-toggle-vis" aria-label="Toggle password">
                            <svg id="eye-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember / Forgot --}}
                <div class="auth-meta">
                    <label class="auth-remember">
                        <input type="checkbox" name="remember" id="remember" class="auth-remember__box">
                        <span class="auth-remember__label">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="auth-forgot">Forgot password?</a>
                </div>

                <button type="submit" class="auth-submit">Sign In</button>
            </form>

            <p class="auth-footer-text">
                Not a member yet? <a href="{{ route('register') }}">Join now →</a>
            </p>
        </div>

        {{-- ── Image panel ──────────────────────────────────── --}}
        <div class="auth-visual" aria-hidden="true">
            <img src="{{ asset('images/gym-bg.png') }}" class="auth-visual__img" alt="">
            <div class="auth-visual__overlay"></div>
            <div class="auth-visual__copy">
                <div class="auth-visual__rule"></div>
                <h2 class="auth-visual__headline">
                    Unleash Your<br><span>True Potential</span>
                </h2>
                <p class="auth-visual__desc">
                    Join the community. Train harder, recover faster, and achieve what you thought was impossible.
                </p>
            </div>
        </div>

    </div>

    <script>
    (function () {
        const pw   = document.getElementById('login-password');
        const btn  = document.getElementById('togglePw');
        const icon = document.getElementById('eye-icon');

        const eyeOpen = `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        const eyeOff  = `<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;

        btn.addEventListener('click', () => {
            const hidden = pw.type === 'password';
            pw.type = hidden ? 'text' : 'password';
            icon.innerHTML = hidden ? eyeOff : eyeOpen;
        });
    })();
    </script>

    @fluxScripts
    @livewireScripts
</body>
</html>