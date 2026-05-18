<div>
<style>
.pub-btn-primary {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    background: #fbbf24; color: #000;
    font-size: 13px; font-weight: 700; letter-spacing: 0.02em;
    padding: 8px 18px; border-radius: 6px; border: none;
    text-decoration: none;
    transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}
.pub-btn-primary:hover {
    background: #f59e0b; color: #000;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(251,191,36,0.3);
}
.pub-btn-primary:active { transform: translateY(0); }

.pub-btn-outline {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    background: transparent; color: #fff;
    font-size: 13px; font-weight: 700; letter-spacing: 0.02em;
    padding: 8px 18px; border-radius: 6px; border: 2px solid #3f3f46;
    text-decoration: none;
    transition: border-color 0.2s, color 0.2s;
    cursor: pointer;
}
.pub-btn-outline:hover { border-color: #fbbf24; color: #fbbf24; }

/* ── Coach booking date picker ── */
[x-cloak] { display: none !important; }

.bk-dp-anim   { transition: opacity 0.16s ease, transform 0.16s ease; }
.bk-dp-hidden { opacity: 0; transform: translateY(-5px) scale(0.98); }
.bk-dp-visible{ opacity: 1; transform: translateY(0)    scale(1);    }

.bk-datepicker-panel {
    border: 1px solid rgba(251,191,36,0.28);
    border-radius: 14px;
    background:
        radial-gradient(circle at 80% 0%, rgba(251,191,36,0.07), transparent 40%),
        rgba(10,10,10,0.98);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 24px 56px rgba(0,0,0,0.65), 0 0 32px rgba(251,191,36,0.06);
    padding: 14px 16px 16px;
}

.bk-dp-nav-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 4px;
}

.bk-dp-month-row {
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
}

.bk-dp-year {
    flex: 1;
    text-align: center;
    color: #fbbf24;
    font-family: 'Barlow Condensed', system-ui, sans-serif;
    font-size: 20px;
    font-weight: 900;
    letter-spacing: 0.04em;
    line-height: 1;
}

.bk-dp-month {
    flex: 1;
    text-align: center;
    color: #fff;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

.bk-dp-navbtn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.09);
    background: rgba(255,255,255,0.04);
    color: #888;
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
}
.bk-dp-navbtn:hover {
    border-color: rgba(251,191,36,0.40);
    background: rgba(251,191,36,0.08);
    color: #fbbf24;
}

.bk-dp-weekdays {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    gap: 2px;
    margin-bottom: 4px;
}
.bk-dp-weekdays span {
    text-align: center;
    color: #505050;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 4px 0;
}

.bk-dp-grid {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    gap: 2px;
}

.bk-dp-day {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 34px;
    border-radius: 8px;
    border: 1px solid transparent;
    background: transparent;
    color: #c8c8c8;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.12s, border-color 0.12s, color 0.12s;
}
.bk-dp-day.is-empty { pointer-events: none; }
.bk-dp-day:not(.is-empty):not(.is-past):not(.is-selected):hover {
    background: rgba(251,191,36,0.10);
    border-color: rgba(251,191,36,0.22);
    color: #fbbf24;
}
.bk-dp-day.is-today {
    border-color: rgba(251,191,36,0.38);
    color: #fbbf24;
}
.bk-dp-day.is-selected {
    background: #fbbf24;
    border-color: #fbbf24;
    color: #050505;
    font-weight: 800;
    box-shadow: 0 4px 14px rgba(251,191,36,0.28);
}
.bk-dp-day.is-past {
    color: #2e2e2e;
    cursor: not-allowed;
    pointer-events: none;
}

/* Time row */
.bk-dp-time-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(255,255,255,0.07);
}
.bk-dp-time-icon {
    width: 15px;
    height: 15px;
    color: #fbbf24;
    flex-shrink: 0;
}
.bk-dp-time-selects {
    display: flex;
    align-items: center;
    gap: 6px;
    flex: 1;
}
.bk-dp-select {
    flex: 1;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.10);
    border-radius: 8px;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    padding: 6px 8px;
    outline: none;
    cursor: pointer;
    transition: border-color 0.15s;
    appearance: none;
    -webkit-appearance: none;
    text-align: center;
}
.bk-dp-select:focus {
    border-color: #fbbf24;
    box-shadow: 0 0 0 2px rgba(251,191,36,0.12);
}
.bk-dp-select option { background: #111; color: #fff; }
.bk-dp-time-sep {
    color: #fbbf24;
    font-size: 16px;
    font-weight: 900;
    line-height: 1;
}
</style>

<div class="mx-auto max-w-7xl space-y-6">
    <section class="relative overflow-hidden rounded-lg bg-[#080808] px-5 py-6 ring-1 ring-amber-400/10 sm:px-7">
        <div class="absolute inset-y-0 right-0 hidden w-1/2 bg-[url('/images/hero-gym.jpg')] bg-cover bg-center opacity-15 mix-blend-luminosity lg:block"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_15%,rgba(251,191,36,0.14),transparent_34%)]"></div>
        <div class="relative">
            <span class="inline-flex rounded-full border border-amber-400/25 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">
                Personal Training
            </span>
            <h1 class="mt-4 text-4xl font-black uppercase leading-none text-white sm:text-5xl">Coaches</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-400">Book a focused session with an Amigos Fitness Gym coach.</p>
        </div>
    </section>

    @if(session('success'))<div class="rounded-md border border-emerald-400/25 bg-emerald-400/10 px-4 py-3 text-sm font-semibold text-emerald-300">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="rounded-md border border-red-400/30 bg-red-400/10 px-4 py-3 text-sm font-semibold text-red-300">{{ session('error') }}</div>@endif

    @if($myBookings->count())
    <section class="rounded-lg bg-[#0b0b0b] p-5 ring-1 ring-white/10 sm:p-6">
        <h2 class="text-sm font-black uppercase tracking-[0.16em] text-amber-400">My Upcoming Bookings</h2>
        <div class="mt-4 space-y-3">
            @foreach($myBookings as $booking)
            <div class="flex flex-col gap-3 rounded-md bg-zinc-950/70 px-4 py-3 ring-1 ring-white/5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-bold text-white">{{ $booking->coach->name }}</p>
                    <p class="mt-1 text-xs text-zinc-500">{{ $booking->scheduled_at->format('M j, Y \a\t g:i A') }}</p>
                </div>
                <button wire:click="cancel({{ $booking->id }})" wire:confirm="Cancel this booking?" class="pub-btn-outline">
                    Cancel
                </button>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <section class="grid grid-cols-1 items-stretch gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse($coaches as $coach)
        <article class="flex flex-col rounded-lg bg-[#0b0b0b] p-5 ring-1 ring-white/10 transition hover:ring-amber-400/25">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-md bg-amber-400/10 text-amber-400 ring-1 ring-amber-400/20">
                <span class="text-sm font-black">{{ strtoupper(substr($coach->name, 0, 1)) }}</span>
            </div>
            <div class="mb-4 flex-1">
                <p class="text-lg font-black uppercase text-white">{{ $coach->name }}</p>
                @if($coach->bio)<p class="mt-2 text-sm leading-6 text-zinc-400">{{ $coach->bio }}</p>@endif
                @if($coach->specializations)
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($coach->specializations as $s)
                            <span class="inline-flex rounded-full border border-amber-400/20 bg-amber-400/10 px-2.5 py-1 text-xs font-bold text-amber-300">{{ $s }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
            <button wire:click="openBooking({{ $coach->id }})" class="pub-btn-primary mt-auto w-full justify-center">
                Book Session
            </button>
        </article>
        @empty
        <div class="rounded-lg border border-dashed border-zinc-800 bg-zinc-950/50 p-8 text-center md:col-span-2 lg:col-span-3">
            <p class="text-sm font-semibold text-zinc-300">No coaches available yet</p>
            <p class="mt-1 text-xs text-zinc-600">Check back soon for available coaching sessions.</p>
        </div>
        @endforelse
    </section>

    @if($bookingCoach)
    <div class="fixed inset-0 z-50 flex items-center justify-center px-4" style="background: rgba(0,0,0,0.85); backdrop-filter: blur(4px);">
        <div class="w-full max-w-sm rounded-lg p-6 shadow-2xl ring-1 ring-amber-400/30" style="background:#111111; box-shadow: 0 25px 50px rgba(0,0,0,0.8);">
            <span class="inline-flex rounded-full border border-amber-400/25 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">
                Coach Booking
            </span>
            <h2 class="mt-4 text-xl font-black uppercase text-white">Book with {{ $bookingCoach->name }}</h2>
            <p class="mt-2 text-sm leading-6 text-zinc-400">Select a date and time for your session.</p>

            <div class="mt-5 flex flex-col gap-2"
                x-data="{
                    open: false,
                    dateValue: '',
                    timeHour: '09',
                    timeMinute: '00',
                    panelTop: 0, panelLeft: 0, panelWidth: 0,
                    viewYear: new Date().getFullYear(),
                    viewMonth: new Date().getMonth(),
                    months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
                    hours: Array.from({length: 14}, (_,i) => String(i + 7).padStart(2,'0')),
                    minutes: ['00','15','30','45'],
                    get viewMonthName() { return this.months[this.viewMonth]; },
                    get daysInMonth() { return new Date(this.viewYear, this.viewMonth + 1, 0).getDate(); },
                    get firstDayOfWeek() { return new Date(this.viewYear, this.viewMonth, 1).getDay(); },
                    get calendarCells() {
                        const cells = [];
                        for (let i = 0; i < this.firstDayOfWeek; i++) cells.push(null);
                        for (let d = 1; d <= this.daysInMonth; d++) cells.push(d);
                        return cells;
                    },
                    get displayLabel() {
                        if (!this.dateValue) return '';
                        const [y, m, d] = this.dateValue.split('-').map(Number);
                        const h = parseInt(this.timeHour);
                        const ampm = h >= 12 ? 'PM' : 'AM';
                        const h12 = h % 12 || 12;
                        return this.months[m-1] + ' ' + d + ', ' + y + '  \u00b7  ' + h12 + ':' + this.timeMinute + ' ' + ampm;
                    },
                    isPast(d) {
                        if (!d) return false;
                        const sel = new Date(this.viewYear, this.viewMonth, d);
                        const today = new Date(); today.setHours(0,0,0,0);
                        return sel < today;
                    },
                    isToday(d) {
                        const t = new Date();
                        return d === t.getDate() && this.viewMonth === t.getMonth() && this.viewYear === t.getFullYear();
                    },
                    isSelected(d) {
                        if (!this.dateValue || !d) return false;
                        const [y, m, day] = this.dateValue.split('-').map(Number);
                        return d === day && this.viewMonth === m - 1 && this.viewYear === y;
                    },
                    selectDay(d) {
                        if (!d || this.isPast(d)) return;
                        const mm = String(this.viewMonth + 1).padStart(2,'0');
                        const dd = String(d).padStart(2,'0');
                        this.dateValue = this.viewYear + '-' + mm + '-' + dd;
                        this.syncWire();
                    },
                    syncWire() {
                        if (!this.dateValue) return;
                        $wire.set('scheduledDate', this.dateValue + 'T' + this.timeHour + ':' + this.timeMinute);
                    },
                    prevMonth() {
                        if (this.viewMonth === 0) { this.viewMonth = 11; this.viewYear--; } else this.viewMonth--;
                    },
                    nextMonth() {
                        if (this.viewMonth === 11) { this.viewMonth = 0; this.viewYear++; } else this.viewMonth++;
                    },
                    prevYear() { this.viewYear--; },
                    nextYear() { this.viewYear++; },
                    toggleOpen() {
                        if (!this.open) {
                            const rect = this.$refs.bktrigger.getBoundingClientRect();
                            this.panelTop   = rect.bottom + window.scrollY + 6;
                            this.panelLeft  = rect.left  + window.scrollX;
                            this.panelWidth = Math.max(rect.width, 300);
                        }
                        this.open = !this.open;
                    },
                    init() {
                        document.addEventListener('click', (e) => {
                            if (!this.$el.contains(e.target) && !document.getElementById('bk-dp-panel')?.contains(e.target)) {
                                this.open = false;
                            }
                        });
                    }
                }">

                <label class="text-xs font-bold uppercase tracking-[0.14em] text-zinc-500">Date &amp; Time</label>

                {{-- Trigger --}}
                <button type="button"
                    x-ref="bktrigger"
                    @click="toggleOpen()"
                    :class="open ? 'border-amber-400 ring-2 ring-amber-400/15' : 'border-zinc-800'"
                    class="flex w-full items-center justify-between gap-2 rounded-md border bg-zinc-950 px-3 py-2.5 text-left text-sm font-semibold text-white outline-none transition">
                    <span x-show="displayLabel" x-text="displayLabel" class="truncate text-white"></span>
                    <span x-show="!displayLabel" class="text-zinc-600">Select date &amp; time</span>
                    <svg class="h-4 w-4 shrink-0 transition" :class="open ? 'text-amber-400' : 'text-zinc-600'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8"  y1="2" x2="8"  y2="6"/>
                        <line x1="3"  y1="10" x2="21" y2="10"/>
                    </svg>
                </button>

                {{-- Teleported calendar panel --}}
                <template x-teleport="body">
                    <div id="bk-dp-panel"
                        x-show="open"
                        x-cloak
                        x-transition:enter="bk-dp-anim"
                        x-transition:enter-start="bk-dp-hidden"
                        x-transition:enter-end="bk-dp-visible"
                        x-transition:leave="bk-dp-anim"
                        x-transition:leave-start="bk-dp-visible"
                        x-transition:leave-end="bk-dp-hidden"
                        :style="`position: absolute; top: ${panelTop}px; left: ${panelLeft}px; width: ${panelWidth}px; min-width: 300px; z-index: 9999;`"
                        class="bk-datepicker-panel">

                        {{-- Year nav --}}
                        <div class="bk-dp-nav-row">
                            <button type="button" @click="prevYear()" class="bk-dp-navbtn" aria-label="Previous year">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="11 17 6 12 11 7"/><polyline points="18 17 13 12 18 7"/>
                                </svg>
                            </button>
                            <span class="bk-dp-year" x-text="viewYear"></span>
                            <button type="button" @click="nextYear()" class="bk-dp-navbtn" aria-label="Next year">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Month nav --}}
                        <div class="bk-dp-nav-row bk-dp-month-row">
                            <button type="button" @click="prevMonth()" class="bk-dp-navbtn" aria-label="Previous month">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="15 18 9 12 15 6"/>
                                </svg>
                            </button>
                            <span class="bk-dp-month" x-text="viewMonthName"></span>
                            <button type="button" @click="nextMonth()" class="bk-dp-navbtn" aria-label="Next month">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="9 18 15 12 9 6"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Weekday headers --}}
                        <div class="bk-dp-weekdays">
                            @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $wd)
                                <span>{{ $wd }}</span>
                            @endforeach
                        </div>

                        {{-- Day grid --}}
                        <div class="bk-dp-grid">
                            <template x-for="(cell, idx) in calendarCells" :key="idx">
                                <button type="button"
                                    @click="selectDay(cell)"
                                    :disabled="!cell || isPast(cell)"
                                    :class="{
                                        'bk-dp-day': true,
                                        'is-empty':    !cell,
                                        'is-selected': isSelected(cell),
                                        'is-today':    isToday(cell) && !isSelected(cell),
                                        'is-past':     cell && isPast(cell)
                                    }"
                                    x-text="cell || ''">
                                </button>
                            </template>
                        </div>

                        {{-- Time selector --}}
                        <div class="bk-dp-time-row">
                            <svg class="bk-dp-time-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <div class="bk-dp-time-selects">
                                <select x-model="timeHour" @change="syncWire()" class="bk-dp-select">
                                    <template x-for="h in hours" :key="h">
                                        <option :value="h" x-text="(parseInt(h) % 12 || 12) + ' ' + (parseInt(h) >= 12 ? 'PM' : 'AM')"></option>
                                    </template>
                                </select>
                                <span class="bk-dp-time-sep">:</span>
                                <select x-model="timeMinute" @change="syncWire()" class="bk-dp-select">
                                    <template x-for="min in minutes" :key="min">
                                        <option :value="min" x-text="min"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                    </div>
                </template>

                @error('scheduledDate')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
            </div>


            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <button wire:click="confirmBooking" class="pub-btn-primary flex-1 justify-center">Confirm Booking</button>
                <button wire:click="closeBooking" class="pub-btn-outline flex-1 justify-center">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>
</div>