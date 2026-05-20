<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Membership Card</title>
<style>
/*
 * Matches the web card at /portal/card as closely as DomPDF allows.
 * DomPDF: 1 CSS px = 1 PDF pt = 1/72in. Page = 242pt × 153pt.
 */
@page { size: 85.6mm 54mm landscape; margin: 0; }
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: Arial, Helvetica, sans-serif;
    width: 242pt;
    height: 153pt;
    background-color: #111111;
    color: #fff;
}

/* Gold top stripe — matches the web gradient */
.stripe {
    display: block;
    width: 100%;
    height: 3pt;
    background-color: #e8a020;
    font-size: 0; line-height: 0;
}

/* Content wrapper — below the stripe */
.content {
    padding: 8pt 10pt 8pt 10pt;
}

/* Two-column layout */
.card-table { width: 100%; border-collapse: collapse; }
.col-left { vertical-align: top; padding-right: 6pt; }
.col-right { vertical-align: top; width: 72pt; text-align: center; }

/* ── Logo + "MEMBERSHIP CARD" ────────────────────────── */
.logo-row { margin-bottom: 8pt; }
.logo-img { height: 14pt; width: auto; display: inline-block; vertical-align: middle; }
.card-label {
    display: block;
    font-size: 5pt; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.18em;
    color: rgba(232,160,32,0.65);
    margin-top: 2pt;
}

/* ── Member name (hero) ──────────────────────────────── */
.name {
    font-size: 14pt; font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.02em; line-height: 1.1;
    color: #fff;
    margin-bottom: 2pt;
}
.mid {
    font-family: 'Courier New', monospace;
    font-size: 6pt;
    color: rgba(255,255,255,0.28);
    margin-bottom: 6pt;
}

/* ── Divider ─────────────────────────────────────────── */
.rule {
    display: block; width: 100%; height: 1px;
    background: rgba(255,255,255,0.07);
    margin-bottom: 5pt;
    font-size: 0; line-height: 0;
}

/* ── Plan / Expiry ───────────────────────────────────── */
.meta { width: 100%; border-collapse: collapse; }
.ml { display: block; font-size: 4.5pt; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.16em; color: rgba(255,255,255,0.28); margin-bottom: 1pt; }
.mv-plan { display: block; font-size: 8pt; font-weight: 700; color: #e8a020; }
.mv-exp  { display: block; font-size: 8pt; font-weight: 700; color: #fff; }
.mr { text-align: right; vertical-align: top; }

/* ── Badge ───────────────────────────────────────────── */
.badge {
    display: inline-block;
    font-size: 6pt; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.05em;
    padding: 2pt 7pt;
    margin-bottom: 5pt;
    white-space: nowrap;
}
.b-a { color: #6ee7b7; background: rgba(52,211,153,0.12); border: 1px solid rgba(52,211,153,0.30); }
.b-w { color: #fcd34d; background: rgba(251,191,36,0.12); border: 1px solid rgba(251,191,36,0.35); }
.b-e { color: #fca5a5; background: rgba(248,113,113,0.12); border: 1px solid rgba(248,113,113,0.30); }
.b-n { color: #a1a1aa; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); }

/* ── QR ──────────────────────────────────────────────── */
.qr-wrap {
    display: inline-block;
    background: #fff; padding: 3pt;
    line-height: 0; font-size: 0;
}
.qr-lbl {
    display: block; font-size: 3.5pt; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: rgba(255,255,255,0.20); margin-top: 2pt;
}
</style>
</head>
<body>

<div class="stripe"></div>

<div class="content">
<table class="card-table">
<tr>
  {{-- LEFT: branding + member info --}}
  <td class="col-left">

    {{-- Logo + label --}}
    <div class="logo-row">
      @if($logoBase64)
        <img class="logo-img" src="{{ $logoBase64 }}" alt="Amigos">
      @endif
      <span class="card-label">Membership Card</span>
    </div>

    {{-- Member name + ID --}}
    <div class="name">{{ $user->name }}</div>
    <div class="mid">{{ $memberId }}</div>

    {{-- Divider --}}
    <span class="rule"></span>

    {{-- Plan + Expiry --}}
    @if($user->activeMembership)
      @php $m = $user->activeMembership; @endphp
      <table class="meta"><tr>
        <td><span class="ml">Plan</span><span class="mv-plan">{{ $m->plan?->name ?? '—' }}</span></td>
        <td class="mr"><span class="ml">Expires</span><span class="mv-exp">{{ $m->expires_at?->format('M j, Y') ?? '—' }}</span></td>
      </tr></table>
    @endif

  </td>

  {{-- RIGHT: badge + QR --}}
  <td class="col-right">

    @if($user->activeMembership)
      @php $d = $m->expires_at ? (int) \Illuminate\Support\Carbon::today()->diffInDays($m->expires_at, false) : null; @endphp
      @if($d !== null && $d < 0)<span class="badge b-e">EXPIRED</span>
      @elseif($d !== null && $d <= 7)<span class="badge b-w">EXPIRING</span>
      @else <span class="badge b-a">ACTIVE</span>
      @endif
    @else <span class="badge b-n">NO PLAN</span>
    @endif

    <div class="qr-wrap">{!! $qrHtml !!}</div>
    <span class="qr-lbl">Scan to Verify</span>

  </td>
</tr>
</table>
</div>

</body>
</html>