# Admin Dashboard Revamp — Design Spec
**Date:** 2026-05-23  
**Route:** `/admin/dashboard`  
**Component:** `app/Livewire/Admin/AdminDashboard.php`  
**View:** `resources/views/livewire/admin/admin-dashboard.blade.php`

---

## Overview

Full revamp of the admin dashboard to an industry-standard gym SaaS layout with a global period dropdown filter, period-aware KPI stat cards, two full-width charts (member growth line + revenue bar), and a redesigned Recent Sign-ups table with the custom Alpine.js calendar picker replacing the native `<input type="date">`.

---

## Layout (top-to-bottom)

```
[Header: "Dashboard" title (left) | Period Dropdown (centre-right) | Live Clock (far right)]
[4 KPI Stat Cards — period-aware]
[Member Growth Line Chart — full width]
[Revenue Bar Chart — full width]
[Recent Sign-ups Table — search + custom date picker]
```

---

## 1. Period Filter (Dropdown)

### UI
- Positioned in the header row between the title and the live clock
- Styled as a select-like button: dark bg, white/10 border, gold border on open, chevron icon
- Options: **This Week / This Month / This Year / Custom Range**
- When **Custom Range** is selected, two Alpine.js custom calendar pickers appear inline (From / To), reusing the same teleport-to-body calendar panel pattern from the registration form (`resources/views/livewire/public/registration-form.blade.php`)

### Livewire Properties (new)
```php
public string $period = 'month';       // week | month | year | custom
public string $customStart = '';
public string $customEnd = '';
```

### Period date range resolution (PHP helper)
```
week   → Carbon::now()->startOfWeek() … endOfWeek()
month  → Carbon::now()->startOfMonth() … endOfMonth()
year   → Carbon::now()->startOfYear() … endOfYear()
custom → $customStart … $customEnd (Carbon::parse)
```

### Prior period for % change comparison
```
week   → previous 7-day window
month  → previous calendar month
year   → previous calendar year
custom → equal-length window immediately before the custom range
```

---

## 2. KPI Stat Cards (Period-Aware)

All 4 existing cards remain; their queries are updated to be scoped to the resolved period range.

| Card | Metric | Prior period |
|---|---|---|
| Total Members | `User` where `role=member` and `created_at` within period | Same-length prior window |
| Active Members | `Membership` active during period (`starts_at ≤ periodEnd` AND `expires_at ≥ periodStart`) | Same-length prior window |
| Expiring Soon | Memberships expiring within 7 days of `periodEnd` | Same window in prior period |
| New Members (renamed from "New This Month") | `User` where `role=member`, `created_at` within period | Prior period same length |

The existing `calculatePercentageChange(int $current, int $previous): array` helper is reused unchanged.

---

## 3. Charts

### Chart Library
**Chart.js** loaded via CDN (`https://cdn.jsdelivr.net/npm/chart.js`).  
Each chart is a `<canvas>` element.  
Alpine.js `x-init` initialises the Chart.js instance using data passed from Livewire as `@js($memberGrowthData)` / `@js($revenueData)`.  
`wire:key` on the chart wrapper forces Alpine to re-init when the period changes.

### Chart 3a — Member Growth (Line, full-width)
- **X-axis:** time buckets — days for week, weeks for month, months for year/custom
- **Y-axis:** count of new member registrations (`User`, `role=member`, `created_at`) per bucket
- **Style:** gold line `#fbbf24`, `tension: 0.4`, no fill, dark grid `rgba(255,255,255,0.05)`, white tick labels, no legend
- **Data shape from PHP:**
```php
$memberGrowthData = [
    'labels' => ['Mon', 'Tue', ...],   // string labels per bucket
    'values' => [3, 7, 2, ...],        // int counts per bucket
];
```

### Chart 3b — Revenue (Bar, full-width)
- **X-axis:** same time buckets as member growth chart
- **Y-axis:** sum of `Payment.amount` (centavos → display as ₱ pesos, divide by 100) where `status = paid`, `created_at` within period, grouped by bucket
- **Style:** gold bars `#fbbf24`, bar radius 4px, dark grid, white tick labels (₱ prefix)
- **Data shape from PHP:**
```php
$revenueData = [
    'labels' => ['Mon', 'Tue', ...],
    'values' => [1500.00, 3200.00, ...],  // float pesos (amount / 100)
];
```

### Bucket logic
```
period=week   → 7 daily buckets  (label: 'Mon'…'Sun')
period=month  → weekly buckets   (label: 'Week 1'…'Week 4/5')
period=year   → 12 monthly buckets (label: 'Jan'…'Dec')
period=custom → auto: ≤14 days → daily, ≤90 days → weekly, else monthly
```

---

## 4. Recent Sign-ups Table

### Table columns (unchanged)
Name / Email / Plan / Joined

### Search input (unchanged)
`wire:model.live.debounce.300ms="search"` — no changes.

### Date picker redesign
Replace native `<input type="date" wire:model.live="dateFilter">` with a custom Alpine.js calendar trigger button + teleported panel, matching the registration form pattern exactly:

- **Trigger button:** styled with `.rg-datepicker-trigger` CSS class (already defined in `resources/views/public/register.blade.php`)
- **Panel:** teleported to `<body>` via `x-teleport="body"`, positioned absolutely using `getBoundingClientRect()`
- **On day select:** calls `$wire.set('dateFilter', val)` — same Livewire property, no backend changes
- **Clear button:** small `×` icon visible when `dateFilter` is not empty; clicking calls `$wire.set('dateFilter', '')`
- **No future dates** disabled (unlike registration form which blocks future — here all past dates are valid, so `isFuture` guard is removed)

The existing `->when($this->dateFilter, fn($q) => $q->whereDate('created_at', $this->dateFilter))` query logic stays unchanged.

### Table scope vs period dropdown
The table's date picker is an independent row-level filter. The period dropdown does **not** pre-filter the table. Both can be active simultaneously.

---

## 5. Files Changed

| File | Change |
|---|---|
| `app/Livewire/Admin/AdminDashboard.php` | Add `$period`, `$customStart`, `$customEnd`; refactor all stat queries to period-aware; add `getMemberGrowthData()` and `getRevenueData()` methods; pass chart data to view |
| `resources/views/livewire/admin/admin-dashboard.blade.php` | Full view rewrite: period dropdown, custom range pickers, Chart.js canvas elements with Alpine init, custom calendar date picker |
| No new files required | CSS reuses existing `.rg-datepicker-*` classes from `register.blade.php` |

---

## 6. Out of Scope

- No new routes or controllers
- No database migrations
- No changes to the stat card component (`x-stat-card`)
- No changes to the registration form
- No chart export / CSV download
- No real-time push updates (Livewire polling not added)
