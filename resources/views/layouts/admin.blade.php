<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} Admin — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
    <style>
        /* ── Admin table styling ── */
        div:has(> table) {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.07);
            background: #080808;
        }
        div:has(> table) table thead tr {
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        div:has(> table) table tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
        }
        div:has(> table) table tbody tr:hover {
            background: rgba(255,255,255,0.03);
        }
        div:has(> table) table tbody tr:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body class="bg-dark-page text-dark antialiased">

<flux:sidebar sticky stashable class="border-r border-gray-700 bg-dark-card text-white overflow-hidden flex flex-col h-screen" style="border-right: 1px solid #374151 !important;">
    <!-- Removed sidebar toggle x button -->

    <div class="px-4 py-4 bg-dark-card border-b border-white/5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-sm hover:opacity-80 transition-opacity">
            <img src="{{ asset('images/amigos1.png') }}" alt="Amigo's Fitness Gym" class="h-8 w-auto">
            <span class="block text-sm font-semibold text-white/80 tracking-wide">Admin Panel</span>
        </a>
    </div>

    <flux:navlist class="bg-dark-card flex-1 overflow-hidden admin-navlist">
        <flux:navlist.item class="admin-nav-item" icon="squares-2x2" href="{{ route('admin.dashboard') }}" :current="request()->routeIs('admin.dashboard')">Dashboard</flux:navlist.item>

        <flux:navlist.group class="admin-nav-group" heading="Members">
            <flux:navlist.item class="admin-nav-item" icon="users" href="{{ route('admin.members.index') }}" :current="request()->routeIs('admin.members.*')">All Members</flux:navlist.item>
            <flux:navlist.item class="admin-nav-item" icon="identification" href="{{ route('admin.plans.index') }}" :current="request()->routeIs('admin.plans.*')">Membership Plans</flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group class="admin-nav-group" heading="Content">
            <flux:navlist.item class="admin-nav-item" icon="user-group" href="{{ route('admin.coaches.index') }}" :current="request()->routeIs('admin.coaches.*')">Coaches</flux:navlist.item>
            <flux:navlist.item class="admin-nav-item" icon="calendar-days" href="{{ route('admin.schedules.index') }}" :current="request()->routeIs('admin.schedules.*')">Class Schedule</flux:navlist.item>
            <flux:navlist.item class="admin-nav-item" icon="ticket" href="{{ route('admin.events.index') }}" :current="request()->routeIs('admin.events.*')">Events</flux:navlist.item>
            <flux:navlist.item class="admin-nav-item" icon="globe-alt" href="{{ route('admin.site-content') }}" :current="request()->routeIs('admin.site-content')">Site Content</flux:navlist.item>
            <flux:navlist.item class="admin-nav-item" icon="document-text" href="{{ route('admin.legal.index') }}" :current="request()->routeIs('admin.legal.*')">Legal Documents</flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group class="admin-nav-group" heading="Communication">
            <flux:navlist.item class="admin-nav-item" icon="envelope" href="{{ route('admin.announcements.index') }}" :current="request()->routeIs('admin.announcements.*')">Announcements</flux:navlist.item>
            <flux:navlist.item class="admin-nav-item" icon="chat-bubble-left-right" href="{{ route('admin.chat') }}" :current="request()->routeIs('admin.chat')">Live Chat</flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group class="admin-nav-group" heading="System">
            <flux:navlist.item class="admin-nav-item" icon="shield-check" href="{{ route('admin.audit-log') }}" :current="request()->routeIs('admin.audit-log')">Audit Log</flux:navlist.item>
        </flux:navlist.group>
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
    <div class="p-6">
        @yield('content')
    </div>
</flux:main>

@fluxScripts
@livewireScripts

@stack('scripts')

<script>
    function initAdminNav() {
        const navItems = document.querySelectorAll('.admin-nav-item');

        navItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove active states from all items
                navItems.forEach(nav => {
                    nav.removeAttribute('aria-current');
                    nav.removeAttribute('data-current');
                    nav.classList.remove('is-active');
                });

                // Set the clicked item as active
                this.classList.add('is-active');
            });
        });
    }

    document.addEventListener('DOMContentLoaded', initAdminNav);
    // Support for Livewire navigations
    document.addEventListener('livewire:navigated', initAdminNav);
</script>
</body>
</html>
