@push('styles')
<style>
    /* ── Custom Date Picker (reused from register.blade.php) ── */
    .rg-datepicker-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        width: 100%;
        padding: 8px 12px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 10px;
        cursor: pointer;
        text-align: left;
        transition: border-color 0.15s, background 0.15s;
    }
    .rg-datepicker-trigger.is-open {
        border-color: #fbbf24 !important;
        background: rgba(255,255,255,0.08);
    }
    .rg-datepicker-value {
        color: #fff;
        font-size: 14px;
    }
    .rg-datepicker-placeholder {
        color: #404040;
        font-size: 14px;
    }
    .rg-datepicker-icon {
        color: #666;
        flex-shrink: 0;
    }
    .rg-datepicker-trigger:hover .rg-datepicker-icon,
    .rg-datepicker-trigger.is-open .rg-datepicker-icon {
        color: #fbbf24;
    }
    .rg-datepicker-panel {
        z-index: 9999;
        border: 1px solid rgba(251,191,36,0.28) !important;
        border-radius: 14px;
        background: #111;
        padding: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.6);
    }
    /* Alpine transition classes */
    .dp-enter       { transition: opacity 0.18s ease, transform 0.18s ease; }
    .dp-enter-start { opacity: 0; transform: translateY(-6px) scale(0.98); }
    .dp-enter-end   { opacity: 1; transform: translateY(0)    scale(1);    }
    .dp-leave       { transition: opacity 0.14s ease, transform 0.14s ease; }
    .dp-leave-start { opacity: 1; transform: translateY(0)    scale(1);    }
    .dp-leave-end   { opacity: 0; transform: translateY(-4px) scale(0.98); }
    .rg-dp-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .rg-dp-year-row  { margin-bottom: 4px; }
    .rg-dp-month-row { margin-bottom: 10px; }
    .rg-dp-year  { color: #fbbf24; font-size: 13px; font-weight: 700; letter-spacing: 0.05em; }
    .rg-dp-month { color: #fff; font-size: 14px; font-weight: 600; }
    .rg-dp-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: transparent;
        border: 1px solid rgba(255,255,255,0.08);
        color: #9ca3af;
        cursor: pointer;
        transition: border-color 0.15s, color 0.15s;
    }
    .rg-dp-nav:hover { border-color: rgba(251,191,36,0.40) !important; color: #fbbf24; }
    .rg-dp-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        margin-bottom: 6px;
    }
    .rg-dp-weekdays span {
        text-align: center;
        font-size: 11px;
        font-weight: 600;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 2px 0;
    }
    .rg-dp-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
    }
    .rg-dp-day {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 30px;
        border-radius: 6px;
        font-size: 13px;
        color: #d1d5db;
        background: transparent;
        border: 1px solid transparent;
        cursor: pointer;
        transition: background 0.12s, border-color 0.12s, color 0.12s;
    }
    .rg-dp-day.is-empty    { pointer-events: none; }
    .rg-dp-day:not(.is-empty):not(.is-future):not(.is-selected):hover {
        background: rgba(251,191,36,0.10);
        border-color: rgba(251,191,36,0.20);
        color: #fbbf24;
    }
    .rg-dp-day.is-today    { border-color: rgba(251,191,36,0.38) !important; color: #fbbf24; }
    .rg-dp-day.is-selected { background: #fbbf24; color: #000; font-weight: 700; border-color: #fbbf24 !important; }
    .rg-dp-day.is-future   { color: #333; cursor: default; pointer-events: none; }
</style>
@endpush

<div>
    {{-- ── Header Row ─────────────────────────────────────────────── --}}
    <div class="mb-8 flex flex-wrap justify-between items-start gap-4">

        {{-- Left: Title --}}
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Dashboard</h1>
            <p class="text-gray-300">Overview of your gym's performance and activity</p>
        </div>

        {{-- Centre: Period Dropdown --}}
        <div
            x-data="{
                open: false,
                selected: @entangle('period').live,
                options: [
                    { value: 'week',   label: 'This Week' },
                    { value: 'month',  label: 'This Month' },
                    { value: 'year',   label: 'This Year' },
                    { value: 'custom', label: 'Custom Range' },
                ],
                get activeLabel() {
                    return this.options.find(o => o.value === this.selected)?.label ?? 'This Month';
                },
                choose(val) {
                    this.selected = val;
                    $wire.set('period', val);
                    this.open = false;
                }
            }"
            class="relative flex flex-col items-end gap-2"
            @click.outside="open = false"
        >
            {{-- Trigger --}}
            <button
                type="button"
                @click="open = !open"
                :class="open ? 'border-amber-500/80' : 'border-white/10'"
                class="flex items-center gap-2 bg-white/5 hover:bg-white/8 border rounded-xl px-4 py-2 text-sm text-white transition-all min-w-[150px] justify-between"
            >
                <span x-text="activeLabel"></span>
                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>

            {{-- Dropdown Panel --}}
            <div
                x-show="open"
                x-transition:enter="dp-enter"
                x-transition:enter-start="dp-enter-start"
                x-transition:enter-end="dp-enter-end"
                x-transition:leave="dp-leave"
                x-transition:leave-start="dp-leave-start"
                x-transition:leave-end="dp-leave-end"
                x-cloak
                class="absolute top-full mt-1 right-0 z-50 w-44 bg-[#111] border border-white/10 rounded-xl shadow-2xl overflow-hidden"
            >
                <template x-for="opt in options" :key="opt.value">
                    <button
                        type="button"
                        @click="choose(opt.value)"
                        :class="selected === opt.value ? 'text-amber-400 bg-amber-500/10' : 'text-gray-300 hover:bg-white/5 hover:text-white'"
                        class="w-full text-left px-4 py-2.5 text-sm transition-colors"
                        x-text="opt.label"
                    ></button>
                </template>
            </div>

            {{-- Custom Range Pickers (shown when custom is selected) --}}
            <div x-show="selected === 'custom'" x-cloak class="flex items-center gap-2 mt-1">

                {{-- From picker --}}
                <div
                    x-data="{
                        open: false,
                        displayValue: @js($customStart),
                        panelTop: 0,
                        panelLeft: 0,
                        panelWidth: 0,
                        viewYear: new Date().getFullYear(),
                        viewMonth: new Date().getMonth(),
                        months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
                        get viewMonthName() { return this.months[this.viewMonth]; },
                        get daysInMonth() { return new Date(this.viewYear, this.viewMonth + 1, 0).getDate(); },
                        get firstDayOfWeek() { return new Date(this.viewYear, this.viewMonth, 1).getDay(); },
                        get calendarCells() {
                            const cells = [];
                            for (let i = 0; i < this.firstDayOfWeek; i++) cells.push(null);
                            for (let d = 1; d <= this.daysInMonth; d++) cells.push(d);
                            return cells;
                        },
                        isToday(d) {
                            const t = new Date();
                            return d === t.getDate() && this.viewMonth === t.getMonth() && this.viewYear === t.getFullYear();
                        },
                        isSelected(d) {
                            if (!this.displayValue || !d) return false;
                            const [y, m, day] = this.displayValue.split('-').map(Number);
                            return d === day && this.viewMonth === m - 1 && this.viewYear === y;
                        },
                        toggleOpen() {
                            if (!this.open) {
                                const rect = this.$refs.triggerFrom.getBoundingClientRect();
                                this.panelTop   = rect.bottom + window.scrollY + 6;
                                this.panelLeft  = rect.left  + window.scrollX;
                                this.panelWidth = Math.max(rect.width, 300);
                            }
                            this.open = !this.open;
                        },
                        selectDay(d) {
                            if (!d) return;
                            const mm = String(this.viewMonth + 1).padStart(2,'0');
                            const dd = String(d).padStart(2,'0');
                            const val = this.viewYear + '-' + mm + '-' + dd;
                            this.displayValue = val;
                            $wire.set('customStart', val);
                            this.open = false;
                        },
                        prevMonth() { if (this.viewMonth === 0) { this.viewMonth = 11; this.viewYear--; } else this.viewMonth--; },
                        nextMonth() { if (this.viewMonth === 11) { this.viewMonth = 0; this.viewYear++; } else this.viewMonth++; },
                        prevYear()  { this.viewYear--; },
                        nextYear()  { this.viewYear++; },
                        formatDisplay(v) {
                            if (!v) return '';
                            const [y, m, d] = v.split('-').map(Number);
                            return this.months[m-1].substring(0,3) + ' ' + d + ', ' + y;
                        },
                        init() {
                            document.addEventListener('click', (e) => {
                                if (!this.$el.contains(e.target) && !document.getElementById('rg-dp-panel-from')?.contains(e.target)) {
                                    this.open = false;
                                }
                            });
                        }
                    }"
                    class="relative"
                >
                    <button
                        type="button"
                        x-ref="triggerFrom"
                        @click="toggleOpen()"
                        :class="open ? 'is-open' : ''"
                        class="rg-datepicker-trigger text-xs py-1.5 px-3 w-36"
                        aria-haspopup="true"
                        :aria-expanded="open"
                    >
                        <span x-text="displayValue ? formatDisplay(displayValue) : 'From'" :class="displayValue ? 'rg-datepicker-value' : 'rg-datepicker-placeholder'"></span>
                        <svg class="rg-datepicker-icon" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </button>

                    <template x-teleport="body">
                        <div id="rg-dp-panel-from"
                            x-show="open"
                            x-transition:enter="dp-enter"
                            x-transition:enter-start="dp-enter-start"
                            x-transition:enter-end="dp-enter-end"
                            x-transition:leave="dp-leave"
                            x-transition:leave-start="dp-leave-start"
                            x-transition:leave-end="dp-leave-end"
                            x-cloak
                            :style="`position: absolute; top: ${panelTop}px; left: ${panelLeft}px; width: ${panelWidth}px; min-width: 300px;`"
                            class="rg-datepicker-panel"
                            role="dialog" aria-label="From date picker">
                            <div class="rg-dp-row rg-dp-year-row">
                                <button type="button" @click="prevYear()" class="rg-dp-nav" aria-label="Previous year">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="11 17 6 12 11 7"/><polyline points="18 17 13 12 18 7"/></svg>
                                </button>
                                <span class="rg-dp-year" x-text="viewYear"></span>
                                <button type="button" @click="nextYear()" class="rg-dp-nav" aria-label="Next year">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg>
                                </button>
                            </div>
                            <div class="rg-dp-row rg-dp-month-row">
                                <button type="button" @click="prevMonth()" class="rg-dp-nav" aria-label="Previous month">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                                </button>
                                <span class="rg-dp-month" x-text="viewMonthName"></span>
                                <button type="button" @click="nextMonth()" class="rg-dp-nav" aria-label="Next month">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                </button>
                            </div>
                            <div class="rg-dp-weekdays">
                                @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $wd)
                                    <span>{{ $wd }}</span>
                                @endforeach
                            </div>
                            <div class="rg-dp-grid">
                                <template x-for="(cell, idx) in calendarCells" :key="idx">
                                    <button type="button"
                                        @click="selectDay(cell)"
                                        :disabled="!cell"
                                        :class="{
                                            'rg-dp-day': true,
                                            'is-empty':    !cell,
                                            'is-selected': isSelected(cell),
                                            'is-today':    isToday(cell) && !isSelected(cell)
                                        }"
                                        x-text="cell || ''">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <span class="text-gray-500 text-xs">to</span>

                {{-- To picker --}}
                <div
                    x-data="{
                        open: false,
                        displayValue: @js($customEnd),
                        panelTop: 0,
                        panelLeft: 0,
                        panelWidth: 0,
                        viewYear: new Date().getFullYear(),
                        viewMonth: new Date().getMonth(),
                        months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
                        get viewMonthName() { return this.months[this.viewMonth]; },
                        get daysInMonth() { return new Date(this.viewYear, this.viewMonth + 1, 0).getDate(); },
                        get firstDayOfWeek() { return new Date(this.viewYear, this.viewMonth, 1).getDay(); },
                        get calendarCells() {
                            const cells = [];
                            for (let i = 0; i < this.firstDayOfWeek; i++) cells.push(null);
                            for (let d = 1; d <= this.daysInMonth; d++) cells.push(d);
                            return cells;
                        },
                        isToday(d) {
                            const t = new Date();
                            return d === t.getDate() && this.viewMonth === t.getMonth() && this.viewYear === t.getFullYear();
                        },
                        isSelected(d) {
                            if (!this.displayValue || !d) return false;
                            const [y, m, day] = this.displayValue.split('-').map(Number);
                            return d === day && this.viewMonth === m - 1 && this.viewYear === y;
                        },
                        toggleOpen() {
                            if (!this.open) {
                                const rect = this.$refs.triggerTo.getBoundingClientRect();
                                this.panelTop   = rect.bottom + window.scrollY + 6;
                                this.panelLeft  = rect.left  + window.scrollX;
                                this.panelWidth = Math.max(rect.width, 300);
                            }
                            this.open = !this.open;
                        },
                        selectDay(d) {
                            if (!d) return;
                            const mm = String(this.viewMonth + 1).padStart(2,'0');
                            const dd = String(d).padStart(2,'0');
                            const val = this.viewYear + '-' + mm + '-' + dd;
                            this.displayValue = val;
                            $wire.set('customEnd', val);
                            this.open = false;
                        },
                        prevMonth() { if (this.viewMonth === 0) { this.viewMonth = 11; this.viewYear--; } else this.viewMonth--; },
                        nextMonth() { if (this.viewMonth === 11) { this.viewMonth = 0; this.viewYear++; } else this.viewMonth++; },
                        prevYear()  { this.viewYear--; },
                        nextYear()  { this.viewYear++; },
                        formatDisplay(v) {
                            if (!v) return '';
                            const [y, m, d] = v.split('-').map(Number);
                            return this.months[m-1].substring(0,3) + ' ' + d + ', ' + y;
                        },
                        init() {
                            document.addEventListener('click', (e) => {
                                if (!this.$el.contains(e.target) && !document.getElementById('rg-dp-panel-to')?.contains(e.target)) {
                                    this.open = false;
                                }
                            });
                        }
                    }"
                    class="relative"
                >
                    <button
                        type="button"
                        x-ref="triggerTo"
                        @click="toggleOpen()"
                        :class="open ? 'is-open' : ''"
                        class="rg-datepicker-trigger text-xs py-1.5 px-3 w-36"
                        aria-haspopup="true"
                        :aria-expanded="open"
                    >
                        <span x-text="displayValue ? formatDisplay(displayValue) : 'To'" :class="displayValue ? 'rg-datepicker-value' : 'rg-datepicker-placeholder'"></span>
                        <svg class="rg-datepicker-icon" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </button>

                    <template x-teleport="body">
                        <div id="rg-dp-panel-to"
                            x-show="open"
                            x-transition:enter="dp-enter"
                            x-transition:enter-start="dp-enter-start"
                            x-transition:enter-end="dp-enter-end"
                            x-transition:leave="dp-leave"
                            x-transition:leave-start="dp-leave-start"
                            x-transition:leave-end="dp-leave-end"
                            x-cloak
                            :style="`position: absolute; top: ${panelTop}px; left: ${panelLeft}px; width: ${panelWidth}px; min-width: 300px;`"
                            class="rg-datepicker-panel"
                            role="dialog" aria-label="To date picker">
                            <div class="rg-dp-row rg-dp-year-row">
                                <button type="button" @click="prevYear()" class="rg-dp-nav" aria-label="Previous year">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="11 17 6 12 11 7"/><polyline points="18 17 13 12 18 7"/></svg>
                                </button>
                                <span class="rg-dp-year" x-text="viewYear"></span>
                                <button type="button" @click="nextYear()" class="rg-dp-nav" aria-label="Next year">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg>
                                </button>
                            </div>
                            <div class="rg-dp-row rg-dp-month-row">
                                <button type="button" @click="prevMonth()" class="rg-dp-nav" aria-label="Previous month">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                                </button>
                                <span class="rg-dp-month" x-text="viewMonthName"></span>
                                <button type="button" @click="nextMonth()" class="rg-dp-nav" aria-label="Next month">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                </button>
                            </div>
                            <div class="rg-dp-weekdays">
                                @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $wd)
                                    <span>{{ $wd }}</span>
                                @endforeach
                            </div>
                            <div class="rg-dp-grid">
                                <template x-for="(cell, idx) in calendarCells" :key="idx">
                                    <button type="button"
                                        @click="selectDay(cell)"
                                        :disabled="!cell"
                                        :class="{
                                            'rg-dp-day': true,
                                            'is-empty':    !cell,
                                            'is-selected': isSelected(cell),
                                            'is-today':    isToday(cell) && !isSelected(cell)
                                        }"
                                        x-text="cell || ''">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

            </div>
        </div>

        {{-- Right: Live Clock --}}
        <div wire:ignore class="text-right" x-data="{
            date: '',
            time: '',
            init() {
                this.updateClock();
                setInterval(() => this.updateClock(), 1000);
            },
            updateClock() {
                const now = new Date();
                this.date = now.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
                this.time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            }
        }">
            <div class="text-xs text-zinc-500 font-medium tracking-wide" x-text="date"></div>
            <div class="text-sm text-zinc-400 font-semibold mt-0.5" x-text="time"></div>
        </div>

    </div>

    {{-- ── KPI Stat Cards ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stat-card
            title="Total Members"
            value="{{ $totalMembers }}"
            icon="people"
            color="gold"
            percentage="{{ $totalMembersChange['value'] }}"
            trend="{{ $totalMembersChange['trend'] }}"
        />
        <x-stat-card
            title="Active Members"
            value="{{ $activeMembers }}"
            icon="activity"
            color="gold"
            percentage="{{ $activeMembersChange['value'] }}"
            trend="{{ $activeMembersChange['trend'] }}"
        />
        <x-stat-card
            title="Expiring Soon"
            value="{{ $expiringSoon }}"
            icon="alert"
            color="gold"
            percentage="{{ $expiringChange['value'] }}"
            trend="{{ $expiringChange['trend'] }}"
        />
        <x-stat-card
            title="New Members"
            value="{{ $newInPeriod }}"
            icon="calendar"
            color="gold"
            percentage="{{ $newInPeriodChange['value'] }}"
            trend="{{ $newInPeriodChange['trend'] }}"
        />
    </div>

    {{-- ── Member Growth Chart ──────────────────────────────────────── --}}
    <div class="backdrop-blur-md border border-white/10 rounded-xl shadow-xl p-5 mb-6"
         wire:key="chart-growth-{{ $period }}-{{ $customStart }}-{{ $customEnd }}"
         x-data="{
             chart: null,
             init() {
                 const ctx = this.$refs.canvas.getContext('2d');
                 this.chart = new Chart(ctx, {
                     type: 'line',
                     data: {
                         labels: @js($memberGrowthData['labels']),
                         datasets: [{
                             data: @js($memberGrowthData['values']),
                             borderColor: '#fbbf24',
                             backgroundColor: 'transparent',
                             tension: 0.4,
                             pointBackgroundColor: '#fbbf24',
                             pointRadius: 3,
                         }]
                     },
                     options: {
                         responsive: true,
                         plugins: { legend: { display: false } },
                         scales: {
                             x: {
                                 grid: { color: 'rgba(255,255,255,0.05)' },
                                 ticks: { color: '#9ca3af' }
                             },
                             y: {
                                 grid: { color: 'rgba(255,255,255,0.05)' },
                                 ticks: { color: '#9ca3af', stepSize: 1 },
                                 beginAtZero: true
                             }
                         }
                     }
                 });
             }
         }">
        <h2 class="text-sm font-semibold text-white mb-4">Member Growth</h2>
        <canvas x-ref="canvas" height="80"></canvas>
    </div>

    {{-- ── Recent Sign-ups Table ────────────────────────────────────── --}}
    <div class="backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-white/10 flex flex-wrap justify-between items-center gap-4">
            <h2 class="text-sm font-semibold text-white">Recent Sign-ups</h2>
            <div class="flex items-center gap-3">
                {{-- Search --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none z-10">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search members…"
                        class="bg-white/5 border border-white/10 text-white placeholder-gray-500 pl-10 pr-4 py-2 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 backdrop-blur-md transition-all w-64 shadow-inner">
                </div>

                {{-- Custom Alpine.js date picker for dateFilter --}}
                <div
                    x-data="{
                        open: false,
                        displayValue: @js($dateFilter),
                        panelTop: 0,
                        panelLeft: 0,
                        panelWidth: 0,
                        viewYear: new Date().getFullYear(),
                        viewMonth: new Date().getMonth(),
                        months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
                        get viewMonthName() { return this.months[this.viewMonth]; },
                        get daysInMonth() { return new Date(this.viewYear, this.viewMonth + 1, 0).getDate(); },
                        get firstDayOfWeek() { return new Date(this.viewYear, this.viewMonth, 1).getDay(); },
                        get calendarCells() {
                            const cells = [];
                            for (let i = 0; i < this.firstDayOfWeek; i++) cells.push(null);
                            for (let d = 1; d <= this.daysInMonth; d++) cells.push(d);
                            return cells;
                        },
                        isToday(d) {
                            const t = new Date();
                            return d === t.getDate() && this.viewMonth === t.getMonth() && this.viewYear === t.getFullYear();
                        },
                        isSelected(d) {
                            if (!this.displayValue || !d) return false;
                            const [y, m, day] = this.displayValue.split('-').map(Number);
                            return d === day && this.viewMonth === m - 1 && this.viewYear === y;
                        },
                        toggleOpen() {
                            if (!this.open) {
                                const rect = this.$refs.triggerDate.getBoundingClientRect();
                                this.panelTop   = rect.bottom + window.scrollY + 6;
                                this.panelLeft  = rect.left  + window.scrollX;
                                this.panelWidth = Math.max(rect.width, 300);
                            }
                            this.open = !this.open;
                        },
                        selectDay(d) {
                            if (!d) return;
                            const mm = String(this.viewMonth + 1).padStart(2,'0');
                            const dd = String(d).padStart(2,'0');
                            const val = this.viewYear + '-' + mm + '-' + dd;
                            this.displayValue = val;
                            $wire.set('dateFilter', val);
                            this.open = false;
                        },
                        clearDate() {
                            this.displayValue = '';
                            $wire.set('dateFilter', '');
                        },
                        prevMonth() { if (this.viewMonth === 0) { this.viewMonth = 11; this.viewYear--; } else this.viewMonth--; },
                        nextMonth() { if (this.viewMonth === 11) { this.viewMonth = 0; this.viewYear++; } else this.viewMonth++; },
                        prevYear()  { this.viewYear--; },
                        nextYear()  { this.viewYear++; },
                        formatDisplay(v) {
                            if (!v) return '';
                            const [y, m, d] = v.split('-').map(Number);
                            return this.months[m-1].substring(0,3) + ' ' + d + ', ' + y;
                        },
                        init() {
                            const existing = @js($dateFilter);
                            if (existing) {
                                this.displayValue = existing;
                                const [y, m] = existing.split('-').map(Number);
                                this.viewYear  = y;
                                this.viewMonth = m - 1;
                            }
                            document.addEventListener('click', (e) => {
                                if (!this.$el.contains(e.target) && !document.getElementById('rg-dp-panel-date')?.contains(e.target)) {
                                    this.open = false;
                                }
                            });
                        }
                    }"
                    class="flex items-center gap-1"
                >
                    <button
                        type="button"
                        x-ref="triggerDate"
                        @click="toggleOpen()"
                        :class="open ? 'is-open' : ''"
                        class="rg-datepicker-trigger"
                        style="width: 180px;"
                        aria-haspopup="true"
                        :aria-expanded="open"
                    >
                        <span x-text="displayValue ? formatDisplay(displayValue) : ''" class="rg-datepicker-value"></span>
                        <span x-show="!displayValue" class="rg-datepicker-placeholder">Filter by date</span>
                        <svg class="rg-datepicker-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </button>

                    {{-- Clear button --}}
                    <button
                        type="button"
                        x-show="displayValue"
                        @click="clearDate()"
                        class="flex items-center justify-center w-7 h-7 rounded-lg text-gray-500 hover:text-white hover:bg-white/10 transition-colors flex-shrink-0"
                        title="Clear date filter"
                        aria-label="Clear date filter"
                    >
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>

                    <template x-teleport="body">
                        <div id="rg-dp-panel-date"
                            x-show="open"
                            x-transition:enter="dp-enter"
                            x-transition:enter-start="dp-enter-start"
                            x-transition:enter-end="dp-enter-end"
                            x-transition:leave="dp-leave"
                            x-transition:leave-start="dp-leave-start"
                            x-transition:leave-end="dp-leave-end"
                            x-cloak
                            :style="`position: absolute; top: ${panelTop}px; left: ${panelLeft}px; width: ${panelWidth}px; min-width: 300px;`"
                            class="rg-datepicker-panel"
                            role="dialog" aria-label="Date picker">
                            <div class="rg-dp-row rg-dp-year-row">
                                <button type="button" @click="prevYear()" class="rg-dp-nav" aria-label="Previous year">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="11 17 6 12 11 7"/><polyline points="18 17 13 12 18 7"/></svg>
                                </button>
                                <span class="rg-dp-year" x-text="viewYear"></span>
                                <button type="button" @click="nextYear()" class="rg-dp-nav" aria-label="Next year">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/></svg>
                                </button>
                            </div>
                            <div class="rg-dp-row rg-dp-month-row">
                                <button type="button" @click="prevMonth()" class="rg-dp-nav" aria-label="Previous month">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                                </button>
                                <span class="rg-dp-month" x-text="viewMonthName"></span>
                                <button type="button" @click="nextMonth()" class="rg-dp-nav" aria-label="Next month">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                </button>
                            </div>
                            <div class="rg-dp-weekdays">
                                @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $wd)
                                    <span>{{ $wd }}</span>
                                @endforeach
                            </div>
                            <div class="rg-dp-grid">
                                <template x-for="(cell, idx) in calendarCells" :key="idx">
                                    <button type="button"
                                        @click="selectDay(cell)"
                                        :disabled="!cell"
                                        :class="{
                                            'rg-dp-day': true,
                                            'is-empty':    !cell,
                                            'is-selected': isSelected(cell),
                                            'is-today':    isToday(cell) && !isSelected(cell)
                                        }"
                                        x-text="cell || ''">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div>
            <table class="w-full text-sm">
                <thead class="border-b border-white/10">
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Name</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Email</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Plan</th>
                        <th class="text-left text-xs font-medium text-gray-300 uppercase tracking-wide py-3 px-4">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($recentSignups as $member)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-3 px-4 font-medium text-dark">{{ $member->name }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ $member->email }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ $member->activeMembership?->plan?->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $member->created_at->format('M j, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400">
                                <p>No members yet</p>
                                <p class="text-xs mt-0.5">New sign-ups will appear here</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
