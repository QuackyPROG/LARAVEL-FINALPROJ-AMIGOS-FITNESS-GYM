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

    <flux:navlist class="bg-dark-card flex-1 overflow-hidden">
        <flux:navlist.item icon="home" href="{{ route('portal.dashboard') }}" :current="request()->routeIs('portal.dashboard')">Dashboard</flux:navlist.item>
        <flux:navlist.item icon="credit-card" href="{{ route('portal.card') }}" :current="request()->routeIs('portal.card')">My Card</flux:navlist.item>
        <flux:navlist.item icon="calendar" href="{{ route('portal.coaches') }}" :current="request()->routeIs('portal.coaches')">Book a Coach</flux:navlist.item>
        <flux:navlist.item icon="clock" href="{{ route('portal.schedule') }}" :current="request()->routeIs('portal.schedule')">Class Schedule</flux:navlist.item>
        <flux:navlist.item icon="star" href="{{ route('portal.events') }}" :current="request()->routeIs('portal.events')">Events</flux:navlist.item>
        <flux:navlist.item icon="document-check" href="{{ route('portal.my-membership') }}" :current="request()->routeIs('portal.my-membership')">My Membership</flux:navlist.item>
        <flux:navlist.item icon="chat-bubble-left-right" href="{{ route('portal.support') }}" :current="request()->routeIs('portal.support')">Support</flux:navlist.item>
    </flux:navlist>

    <div class="bg-dark-card border-t border-white/5 p-3 flex flex-col gap-1">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-zinc-500 rounded-md hover:text-zinc-300 hover:bg-white/5 transition-colors">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Visit Site
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-zinc-500 rounded-md hover:text-zinc-300 hover:bg-white/5 transition-colors">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</flux:sidebar>

<flux:main>
    <flux:header class="!border-white/5 !bg-dark-card !text-white backdrop-blur">
        <flux:sidebar.toggle icon="bars-3" />
        <a href="{{ url('/') }}" class="ml-auto flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-zinc-500 rounded-md hover:text-zinc-300 hover:bg-white/5 transition-colors">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Visit Site
        </a>
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