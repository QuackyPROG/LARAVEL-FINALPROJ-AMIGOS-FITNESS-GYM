<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Member Portal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }

        [data-flux-sidebar] a,
        [data-flux-sidebar] button { border-radius: 6px !important; }

        [data-flux-sidebar] [aria-current="page"] {
            background: var(--brand-gold-dim) !important;
            color: var(--brand-gold) !important;
        }
    </style>
</head>
<body class="bg-dark-page text-dark antialiased">

<flux:sidebar sticky stashable class="border-r border-gray-700 bg-dark-card text-white overflow-hidden flex flex-col h-screen" style="border-right: 1px solid #374151 !important;">

    <div class="px-4 py-4 bg-dark-card border-b border-white/5">
        <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3 text-sm hover:opacity-80 transition-opacity">
            <img src="{{ asset('images/amigos1.png') }}" alt="Amigo's Fitness Gym" class="h-8 w-auto">
            <span class="block text-sm font-semibold text-white/80 tracking-wide">Member Portal</span>
        </a>
    </div>

    <flux:navlist class="bg-dark-card flex-1 overflow-y-auto">
        <flux:navlist.item icon="home" href="{{ route('portal.dashboard') }}" :current="request()->routeIs('portal.dashboard')">Dashboard</flux:navlist.item>
        <flux:navlist.item icon="credit-card" href="{{ route('portal.card') }}" :current="request()->routeIs('portal.card')">My Card</flux:navlist.item>
        <flux:navlist.item icon="calendar" href="{{ route('portal.coaches') }}" :current="request()->routeIs('portal.coaches')">Book a Coach</flux:navlist.item>
        <flux:navlist.item icon="clock" href="{{ route('portal.schedule') }}" :current="request()->routeIs('portal.schedule')">Class Schedule</flux:navlist.item>
        <flux:navlist.item icon="star" href="{{ route('portal.events') }}" :current="request()->routeIs('portal.events')">Events</flux:navlist.item>
        <flux:navlist.item icon="document-check" href="{{ route('portal.my-membership') }}" :current="request()->routeIs('portal.my-membership')">My Membership</flux:navlist.item>
        <flux:navlist.item icon="chat-bubble-left-right" href="{{ route('portal.support') }}" :current="request()->routeIs('portal.support')">Support</flux:navlist.item>
    </flux:navlist>

    <div class="bg-dark-card border-t border-white/[0.06] p-3">
        {{-- User identity card --}}
        <div class="flex items-center gap-3 px-2 py-2.5 rounded-xl hover:bg-white/[0.04] transition-colors">
            <div class="w-8 h-8 rounded-full bg-[#111] border border-amber-500/35 flex items-center justify-center flex-shrink-0">
                <span class="text-[13px] font-bold text-amber-400 leading-none select-none">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[13px] font-semibold text-white/80 truncate leading-snug">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-zinc-500 truncate leading-snug capitalize">{{ auth()->user()->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Sign out" class="flex items-center justify-center w-7 h-7 rounded-md text-zinc-600 hover:text-red-400 hover:bg-red-500/10 transition-colors">
                    <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
        <a href="{{ url('/') }}" class="flex items-center gap-2 px-2 py-1.5 mt-0.5 rounded-lg text-[11px] text-zinc-600 hover:text-zinc-400 hover:bg-white/[0.04] transition-colors">
            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Visit Site
        </a>
    </div>
</flux:sidebar>

<flux:main>
    <flux:header class="!border-white/5 !bg-dark-card !text-white">
        <flux:sidebar.toggle icon="bars-3" />
    </flux:header>

    <div class="min-h-screen bg-dark-page px-4 py-6 sm:px-6 lg:px-8">
        @yield('content')
    </div>
</flux:main>

<livewire:portal.chat-widget />

@fluxScripts
@livewireScripts
</body>
</html>