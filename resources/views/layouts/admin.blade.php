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

    <flux:navlist class="bg-dark-card flex-1 overflow-y-auto admin-navlist">
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

        <flux:navlist.group class="admin-nav-group" heading="Revenue">
            <flux:navlist.item class="admin-nav-item" icon="banknotes" href="{{ route('admin.revenue.index') }}" :current="request()->routeIs('admin.revenue.*')">Revenue Report</flux:navlist.item>
            <flux:navlist.item class="admin-nav-item" icon="chart-bar" href="{{ route('admin.sales-summary.index') }}" :current="request()->routeIs('admin.sales-summary.*')">Sales Summary</flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group class="admin-nav-group" heading="System">
            <flux:navlist.item class="admin-nav-item" icon="shield-check" href="{{ route('admin.audit-log') }}" :current="request()->routeIs('admin.audit-log')">Audit Log</flux:navlist.item>
        </flux:navlist.group>
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
