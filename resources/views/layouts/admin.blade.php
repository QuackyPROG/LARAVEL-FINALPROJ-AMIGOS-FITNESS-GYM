<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} Admin — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>

<flux:sidebar sticky stashable>
    <flux:sidebar.toggle icon="x-mark" />

    <div>
        <a href="{{ route('admin.dashboard') }}">
            <span>Amigos Admin</span>
        </a>
        <p>Management Panel</p>
    </div>

    <flux:navlist>
        <flux:navlist.item icon="squares-2x2" href="{{ route('admin.dashboard') }}" :current="request()->routeIs('admin.dashboard')">Dashboard</flux:navlist.item>

        <flux:navlist.group heading="Members">
            <flux:navlist.item icon="users" href="{{ route('admin.members.index') }}" :current="request()->routeIs('admin.members.*')">All Members</flux:navlist.item>
            <flux:navlist.item icon="identification" href="{{ route('admin.plans.index') }}" :current="request()->routeIs('admin.plans.*')">Membership Plans</flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group heading="Content">
            <flux:navlist.item icon="user-group" href="{{ route('admin.coaches.index') }}" :current="request()->routeIs('admin.coaches.*')">Coaches</flux:navlist.item>
            <flux:navlist.item icon="calendar-days" href="{{ route('admin.schedules.index') }}" :current="request()->routeIs('admin.schedules.*')">Class Schedule</flux:navlist.item>
            <flux:navlist.item icon="ticket" href="{{ route('admin.events.index') }}" :current="request()->routeIs('admin.events.*')">Events</flux:navlist.item>
            <flux:navlist.item icon="globe-alt" href="{{ route('admin.site-content') }}" :current="request()->routeIs('admin.site-content')">Site Content</flux:navlist.item>
            <flux:navlist.item icon="document-text" href="{{ route('admin.legal.index') }}" :current="request()->routeIs('admin.legal.*')">Legal Documents</flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group heading="Communication">
            <flux:navlist.item icon="envelope" href="{{ route('admin.announcements.index') }}" :current="request()->routeIs('admin.announcements.*')">Announcements</flux:navlist.item>
            <flux:navlist.item icon="chat-bubble-left-right" href="{{ route('admin.chat') }}" :current="request()->routeIs('admin.chat')">Live Chat</flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group heading="System">
            <flux:navlist.item icon="shield-check" href="{{ route('admin.audit-log') }}" :current="request()->routeIs('admin.audit-log')">Audit Log</flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>

    <div>
        <p>{{ auth()->user()->name }}</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Sign out</button>
        </form>
    </div>
</flux:sidebar>

<flux:main>
    <flux:header>
        <flux:sidebar.toggle icon="bars-3" />
        <span>{{ auth()->user()->name }}</span>
    </flux:header>

    <div>
        @yield('content')
    </div>
</flux:main>

@fluxScripts
@livewireScripts
</body>
</html>
