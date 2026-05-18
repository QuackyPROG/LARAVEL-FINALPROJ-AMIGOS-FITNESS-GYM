<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Forgot Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        .glass-card {
            background: rgba(10, 10, 10, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gold-glow {
            box-shadow: 0 0 50px rgba(234, 179, 8, 0.15);
        }

        .bg-scale-subtle {
            animation: slowZoom 20s infinite alternate ease-in-out;
        }

        @keyframes slowZoom {
            0% { transform: scale(1.05); }
            100% { transform: scale(1.15); }
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(234, 179, 8, 0.4); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(234, 179, 8, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(234, 179, 8, 0); }
        }

        .icon-pulse {
            animation: pulse-ring 2.5s ease-in-out infinite;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-black text-white font-sans antialiased overflow-hidden">

    <!-- 1. FULL PAGE BACKGROUND -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <img src="{{ asset('images/gym-bg.png') }}"
             class="w-full h-full object-cover opacity-30 blur-md bg-scale-subtle"
             alt="Background">
        <div class="absolute inset-0 bg-black/60"></div>
    </div>

    <!-- 2. MAIN CARD -->
    <main class="relative z-10 w-full max-w-7xl mx-4 flex flex-col md:flex-row rounded-2xl overflow-hidden glass-card gold-glow">

        <!-- LEFT SIDE: FORM -->
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center relative">

            <!-- Gym Logo -->
            <div class="mb-0 flex justify-center">
                <img src="{{ asset('images/amigos-logo.png') }}" alt="Gym Logo" class="h-35 w-auto object-contain">
            </div>

            @if (session('status'))
                <!-- Success State -->
                <div class="flex flex-col items-center text-center py-4">
                    <div class="mb-6 w-16 h-16 rounded-full bg-yellow-500/10 border border-yellow-500/40 flex items-center justify-center icon-pulse">
                        <svg class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Check Your Inbox</h1>
                    <p class="text-gray-400 text-sm max-w-xs">
                        {{ session('status') }}
                    </p>
                    <a href="{{ route('login') }}"
                       class="mt-8 inline-flex items-center gap-2 text-sm text-yellow-500 hover:text-yellow-400 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Login
                    </a>
                </div>

            @else
                <!-- Default: Request Form -->
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-bold tracking-tight mb-2">Forgot Password?</h1>
                    <p class="text-gray-400 text-sm">Enter your email and we'll send you a reset link.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-900/30 border border-red-500/50 text-red-300 text-sm px-4 py-3 rounded-lg mb-6 flex items-center justify-center gap-3 text-center">
                        <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2 ml-1 text-center md:text-left">Email Address</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 pointer-events-none transition-colors duration-300">
                                <svg class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="you@example.com"
                                class="w-full bg-white/5 border border-gray-700 rounded-xl py-3 pl-12 pr-4 text-lg text-white placeholder-gray-600 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all"
                            >
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full mt-4 bg-gradient-to-r from-yellow-600 via-yellow-500 to-yellow-600 hover:from-yellow-500 hover:via-yellow-400 hover:to-yellow-500 py-3 rounded-xl text-black font-bold uppercase tracking-widest text-md transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 shadow-[0_0_15px_rgba(234,179,8,0.2)] hover:shadow-[0_0_25px_rgba(234,179,8,0.5)]">
                        Send Reset Link
                    </button>

                    <!-- Back to Login -->
                    <div class="text-center pt-1">
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-yellow-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Login
                        </a>
                    </div>
                </form>
            @endif

        </div>

        <!-- RIGHT SIDE: HERO IMAGE -->
        <div class="hidden md:block w-1/2 relative bg-black">
            <img src="{{ asset('images/gym-bg.png') }}"
                 class="absolute inset-0 w-full h-full object-cover object-right"
                 alt="Gym">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>

            <!-- Side Branding -->
            <div class="absolute bottom-12 left-12 right-12 z-10">
                <div class="h-1 w-16 bg-yellow-500 mb-6 shadow-[0_0_10px_rgba(234,179,8,0.8)]"></div>
                <h2 class="text-4xl font-black italic uppercase leading-tight tracking-tighter text-white drop-shadow-lg">
                    Reset &amp; <br> <span class="text-yellow-500">Come Back Stronger</span>
                </h2>
                <p class="mt-4 text-gray-300 text-sm max-w-sm drop-shadow-md">
                    No worries — it happens to the best of us. Let's get you back in the game.
                </p>
            </div>
        </div>

    </main>

    @fluxScripts
    @livewireScripts
</body>
</html>