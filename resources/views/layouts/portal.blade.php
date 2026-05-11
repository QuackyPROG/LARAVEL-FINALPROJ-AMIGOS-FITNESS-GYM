<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Member Portal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

<flux:sidebar sticky stashable>
    <flux:sidebar.toggle icon="x-mark" />

    <div class="px-4 py-4 border-b border-gray-200">
        <a href="{{ route('portal.dashboard') }}" class="font-semibold text-gray-900 text-sm">Amigos Gym</a>
        <p class="text-xs text-gray-400 mt-0.5">Member Portal</p>
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

    <div class="px-4 py-4 border-t border-gray-200 mt-auto">
        <p class="text-sm font-medium text-gray-700 mb-1">{{ auth()->user()->name }}</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-400 underline">Sign out</button>
        </form>
    </div>
</flux:sidebar>

<flux:main>
    <flux:header>
        <flux:sidebar.toggle icon="bars-3" />
        <span class="text-sm text-gray-500 ml-auto">Member Portal</span>
    </flux:header>

    <div class="p-6">
        @yield('content')
    </div>
</flux:main>

<livewire:portal.chat-widget />

@fluxScripts
@livewireScripts
</body>
</html>
