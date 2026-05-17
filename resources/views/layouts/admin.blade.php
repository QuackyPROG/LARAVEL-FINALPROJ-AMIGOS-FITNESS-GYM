<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="font-size: 125%;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} Admin — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
    <style>
        /* Premium Glassmorphism for Table Cards */
        div:has(> table) {
            position: relative;
            background: rgba(10, 10, 10, 0.4) !important;
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            border-radius: 1rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 40px rgba(234, 179, 8, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            z-index: 1;
        }

        /* Subtle Animated Gradient Border via Pseudo-element */
        div:has(> table)::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 1rem;
            padding: 1px;
            background: linear-gradient(45deg, rgba(234, 179, 8, 0.4), rgba(255, 255, 255, 0.05), rgba(234, 179, 8, 0.1));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            z-index: -1;
            pointer-events: none;
            background-size: 200% 200%;
            animation: gradientShimmer 8s ease infinite;
        }

        @keyframes gradientShimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        div:has(> table) table thead,
        div:has(> table) table tbody {
            background: transparent !important;
        }
        
        div:has(> table) table thead {
            background: rgba(0, 0, 0, 0.3) !important;
        }

        div:has(> table) table thead tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        div:has(> table) table tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            transition: all 0.3s ease !important;
            position: relative;
        }
        
        div:has(> table) table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Row Selection / Active State */
        div:has(> table) table tbody tr.selected,
        div:has(> table) table tbody tr:active {
            background: linear-gradient(90deg, rgba(20, 20, 20, 0.9), rgba(234, 179, 8, 0.15)) !important;
            transform: scale(1.005) translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), inset 2px 0 0 rgba(234, 179, 8, 0.8) !important;
            border-color: transparent !important;
            z-index: 10;
        }

        div:has(> table) table tbody tr:last-child {
            border-bottom: none !important;
        }
    </style>
</head>
<body class="bg-dark-page text-dark antialiased">

<flux:sidebar sticky stashable class="border-r border-gray-700 bg-dark-card text-white overflow-hidden flex flex-col h-screen" style="border-right: 1px solid #374151 !important;">
    <!-- Removed sidebar toggle x button -->

    <div class="px-4 py-4 bg-dark-card">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-sm hover:opacity-80 transition-opacity">
            <img src="{{ asset('images/amigos1.png') }}" alt="Amigo's Fitness Gym" class="h-8 w-auto filter drop-shadow-md">
            <span class="block font-extrabold uppercase bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-400 bg-clip-text text-transparent">Amigos Admin</span>
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

    <div class="bg-dark-card p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm text-white border border-gray-600 rounded-md hover:bg-gray-700 transition-colors" style="border-color: #4b5563 !important;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Sign out</span>
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
