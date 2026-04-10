<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — @yield('title', 'Welcome')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-white text-gray-900 antialiased">

    <header class="border-b border-gray-200 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="flex items-center justify-between h-14">
                <a href="/" class="font-semibold text-gray-900 text-sm">
                    AmigosFitnessGym
                </a>
                <nav class="flex items-center gap-6">
                    <a href="/#plans" class="text-sm text-gray-500">Plans</a>
                    <a href="/#coaches" class="text-sm text-gray-500">Coaches</a>
                    <a href="/#contact" class="text-sm text-gray-500">Contact</a>
                </nav>
                @guest
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 border border-gray-300 rounded-md px-3 py-1.5">Member Login</a>
                        <a href="{{ route('register') }}" class="text-sm bg-gray-900 text-white rounded-md px-3 py-1.5">Become a Member</a>
                    </div>
                @else
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-700 border border-gray-300 rounded-md px-3 py-1.5">Admin Panel &rarr;</a>
                    @else
                        <a href="{{ route('portal.dashboard') }}" class="text-sm text-gray-700 border border-gray-300 rounded-md px-3 py-1.5">My Dashboard &rarr;</a>
                    @endif
                @endguest
            </div>
        </div>
    </header>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="border-t border-gray-200 bg-white">
        <div class="max-w-5xl mx-auto px-6 py-6">
            <div>
                <p class="font-medium text-gray-900 text-sm">AmigosFitnessGym</p>
                <p class="text-xs text-gray-400 mt-1">© {{ date('Y') }} AmigosFitnessGym. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @fluxScripts
    @livewireScripts
</body>
</html>
