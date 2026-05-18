<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AmigosFitnessGym') }} — Premium Sign In</title>

    <!-- Tailwind & Logic -->
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

        /* Focus state for icons when input is focused */
        input:focus + span svg,
        input:focus ~ span svg {
            color: #eab308;
            filter: drop-shadow(0 0 5px rgba(234, 179, 8, 0.5));
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

    <!-- 2. MAIN LOGIN CARD -->
    <main class="relative z-10 w-full max-w-7xl mx-4 flex flex-col md:flex-row rounded-2xl overflow-hidden glass-card gold-glow">
        
        <!-- LEFT SIDE: LOGIN FORM -->
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center relative">
            
            <!-- Gym Logo -->
            <div class="mb-0 flex justify-center">
                <img src="{{ asset('images/amigos-logo.png') }}" alt="Gym Logo" class="h-35 w-auto object-contain">
            </div>

            <div class="mb-15 text-center">
                <h1 class="text-3xl font-bold tracking-tight mb-2">Welcome Back</h1>
                <p class="text-gray-400 text-sm">Enter your credentials to access your dashboard</p>
            </div>

            @if (session('status'))
                <div class="bg-green-900/30 border border-green-500/50 text-green-300 text-sm px-4 py-3 rounded-lg mb-6 flex items-center justify-center gap-3 text-center">
                    <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-900/30 border border-red-500/50 text-red-300 text-sm px-4 py-3 rounded-lg mb-6 flex items-center justify-center gap-3 text-center">
                    <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                @csrf

                <!-- Username / Email Input -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2 ml-1 text-center md:text-left">Username / Email</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 pointer-events-none transition-colors duration-300">
                            <svg class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <input type="text" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full bg-white/5 border border-gray-700 rounded-xl py-3 pl-12 pr-4 text-lg text-white placeholder-gray-600 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all"
                               placeholder="you@example.com">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2 ml-1 text-center md:text-left">Password</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 pointer-events-none transition-colors duration-300">
                            <svg class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <input type="password" name="password" id="login-password" required
                               class="w-full bg-white/5 border border-gray-700 rounded-xl py-3 pl-12 pr-12 text-lg text-white placeholder-gray-600 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition-all"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 text-gray-500 hover:text-yellow-500 transition-colors focus:outline-none">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mt-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="remember" id="remember" class="peer appearance-none w-4 h-4 border border-gray-600 rounded bg-black/50 checked:bg-yellow-500 checked:border-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500/50 transition-colors">
                            <svg class="absolute w-3 h-3 text-black pointer-events-none opacity-0 peer-checked:opacity-100 left-0.5 top-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-400 group-hover:text-gray-200 transition-colors">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-yellow-500 hover:text-yellow-400 transition-colors">Forgot Password?</a>
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full mt-15 bg-gradient-to-r from-yellow-600 via-yellow-500 to-yellow-600 hover:from-yellow-500 hover:via-yellow-400 hover:to-yellow-500 py-3 rounded-xl text-black font-bold uppercase tracking-widest text-md transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 shadow-[0_0_15px_rgba(234,179,8,0.2)] hover:shadow-[0_0_25px_rgba(234,179,8,0.5)]">
                    Log In
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gray-500">  
                Not a member? <a href="/register" class="text-white font-semibold hover:text-yellow-500 transition-colors underline decoration-gray-600 hover:decoration-yellow-500 underline-offset-4">Join now</a>
            </p>
        </div>

        <!-- RIGHT SIDE: HERO IMAGE -->
        <div class="hidden md:block w-1/2 relative bg-black">
            <img src="{{ asset('images/gym-bg.png') }}" 
                 class="absolute inset-0 w-full h-full object-cover object-right" 
                 alt="Gym">
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
            
            <!-- Side Branding / Text -->
            <div class="absolute bottom-12 left-12 right-12 z-10">
                <div class="h-1 w-16 bg-yellow-500 mb-6 shadow-[0_0_10px_rgba(234,179,8,0.8)]"></div>
                <h2 class="text-4xl font-black italic uppercase leading-tight tracking-tighter text-white drop-shadow-lg">
                    Unleash Your <br> <span class="text-yellow-500">True Potential</span>
                </h2>
                <p class="mt-4 text-gray-300 text-sm max-w-sm drop-shadow-md">
                    Join the elite community. Train harder, recover faster, and achieve the impossible.
                </p>
            </div>
        </div>
    </main>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('login-password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        }
    </script>

    @fluxScripts
    @livewireScripts
</body>
</html>