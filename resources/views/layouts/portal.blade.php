<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Member Portal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        html {
            scrollbar-width: thin;
            scrollbar-color: #fbbf24 #0a0a0a;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb {
            background: #fbbf24;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover { background: #f59e0b; }

        [data-flux-sidebar] a,
        [data-flux-sidebar] button {
            border-radius: 6px !important;
        }

        [data-flux-sidebar] [aria-current="page"] {
            background: rgba(251, 191, 36, 0.12) !important;
            color: #fbbf24 !important;
        }
    </style>
</head>
<body class="bg-black text-white antialiased">

<flux:sidebar sticky stashable class="!bg-[#050505] !text-white">
    <flux:sidebar.toggle icon="x-mark" />

    <div class="px-5 py-5 border-b border-amber-400/10">
        <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/amigos1.png') }}" alt="Amigo's Fitness Gym" class="h-14 w-auto drop-shadow-[0_0_12px_rgba(251,191,36,0.22)]">
            <span class="sr-only">Amigos Fitness Gym</span>
        </a>
        <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-amber-400">Member Portal</p>
    </div>

    <flux:navlist>
        <flux:navlist.item icon="home" href="{{ route('portal.dashboard') }}" :current="request()->routeIs('portal.dashboard')">Dashboard</flux:navlist.item>
        <flux:navlist.item icon="credit-card" href="{{ route('portal.card') }}" :current="request()->routeIs('portal.card')">My Card</flux:navlist.item>
        <flux:navlist.item icon="calendar" href="{{ route('portal.coaches') }}" :current="request()->routeIs('portal.coaches')">Book a Coach</flux:navlist.item>
        <flux:navlist.item icon="clock" href="{{ route('portal.schedule') }}" :current="request()->routeIs('portal.schedule')">Class Schedule</flux:navlist.item>
        <flux:navlist.item icon="star" href="{{ route('portal.events') }}" :current="request()->routeIs('portal.events')">Events</flux:navlist.item>
        <flux:navlist.item icon="document-check" href="{{ route('portal.my-membership') }}" :current="request()->routeIs('portal.my-membership')">My Membership</flux:navlist.item>
        <flux:navlist.item icon="chat-bubble-left-right" href="{{ route('portal.support') }}" :current="request()->routeIs('portal.support')">Support</flux:navlist.item>
    </flux:navlist>

    <div class="mt-auto border-t border-amber-400/10 px-5 py-5">
        <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
        <p class="mb-3 mt-0.5 text-xs text-zinc-500">Signed in member</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-md border-2 border-zinc-700 bg-transparent px-3 py-2 text-xs font-bold uppercase tracking-[0.12em] text-white"
                style="transition: border-color 0.2s, color 0.2s;"
                onmouseenter="this.style.borderColor='#ef4444';this.style.color='#f87171'"
                onmouseleave="this.style.borderColor='';this.style.color=''">
                <svg style="width:13px;height:13px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M18 15l3-3m0 0-3-3m3 3H9" />
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</flux:sidebar>

<flux:main>
    <flux:header class="!border-amber-400/10 !bg-[#050505]/95 !text-white backdrop-blur">
        <flux:sidebar.toggle icon="bars-3" />
        <a href="{{ url('/') }}"
           class="ml-auto inline-flex items-center justify-center gap-2 rounded-md border-2 border-zinc-700 bg-transparent px-3 py-1.5 text-xs font-bold uppercase tracking-[0.12em] text-white no-underline"
           style="transition: border-color 0.2s, color 0.2s;"
           onmouseenter="this.style.borderColor='#fbbf24';this.style.color='#fbbf24'"
           onmouseleave="this.style.borderColor='';this.style.color=''">
            <svg style="width:13px;height:13px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
            Visit Site
        </a>
    </flux:header>

    <div class="min-h-screen bg-[linear-gradient(160deg,#000_0%,#0d0d0d_36%,#171717_100%)] px-4 py-6 sm:px-6 lg:px-8">
        @yield('content')
    </div>
</flux:main>

<livewire:portal.chat-widget />

@fluxScripts
@livewireScripts
</body>
</html>