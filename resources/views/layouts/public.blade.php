<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — @yield('title', 'Welcome')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>

    <header>
        <div>
            <div>
                <a href="/">
                    AmigosFitnessGym
                </a>
                <nav>
                    <a href="/#plans">Plans</a>
                    <a href="/#coaches">Coaches</a>
                    <a href="/#contact">Contact</a>
                </nav>
                @guest
                    <div>
                        <a href="{{ route('login') }}">Member Login</a>
                        <a href="{{ route('register') }}">Become a Member</a>
                    </div>
                @else
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">Admin Panel &rarr;</a>
                    @else
                        <a href="{{ route('portal.dashboard') }}">My Dashboard &rarr;</a>
                    @endif
                @endguest
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div>
            <div>
                <p>AmigosFitnessGym</p>
                <p>© {{ date('Y') }} AmigosFitnessGym. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @fluxScripts
    @livewireScripts
</body>
</html>
