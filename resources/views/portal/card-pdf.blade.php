<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Membership Card — {{ $user->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700;900&family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Barlow', sans-serif;
            background: #050505;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 40px;
        }
        .card {
            width: 420px;
            background: #080808;
            border: 1px solid rgba(251, 191, 36, 0.22);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.7);
        }
        .stripe { height: 4px; background: #fbbf24; }
        .card-body { padding: 28px; }

        .gym-name {
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #fbbf24;
            margin-bottom: 10px;
        }

        .name-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 6px;
        }
        .member-name {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 26px;
            font-weight: 900;
            text-transform: uppercase;
            color: #ffffff;
            line-height: 1.1;
            flex: 1;
        }
        .badge {
            flex-shrink: 0;
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.03em;
            margin-top: 4px;
        }
        .badge-active  { background: rgba(52,211,153,0.10); border: 1px solid rgba(52,211,153,0.25); color: #6ee7b7; }
        .badge-expiring{ background: rgba(251,191,36,0.10);  border: 1px solid rgba(251,191,36,0.35);  color: #fcd34d; }
        .badge-expired { background: rgba(248,113,113,0.10); border: 1px solid rgba(248,113,113,0.30); color: #fca5a5; }
        .badge-none    { background: #18181b; border: 1px solid #3f3f46; color: #d4d4d8; }

        .member-id {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #52525b;
            margin-bottom: 18px;
        }

        .meta-strip {
            display: flex;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 22px;
        }
        .meta-item { flex: 1; padding: 10px 14px; }
        .meta-item + .meta-item { border-left: 1px solid rgba(255,255,255,0.08); }
        .meta-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #52525b;
            margin-bottom: 3px;
        }
        .meta-value {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
        }

        .qr-section { text-align: center; }
        .qr-wrapper {
            display: inline-block;
            background: #ffffff;
            padding: 14px;
            border-radius: 8px;
            border: 1px solid rgba(251,191,36,0.20);
        }
        .qr-wrapper svg { display: block; width: 160px !important; height: 160px !important; }
        .verify-note {
            font-size: 9px;
            font-weight: 600;
            color: #52525b;
            margin-top: 10px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="stripe"></div>
    <div class="card-body">

        <div class="gym-name">Amigos Fitness Gym</div>

        <div class="name-row">
            <div class="member-name">{{ $user->name }}</div>
            @if($user->activeMembership)
                @php
                    $membership = $user->activeMembership;
                    $daysLeft = $membership->expires_at
                        ? (int) \Illuminate\Support\Carbon::today()->diffInDays($membership->expires_at, false)
                        : null;
                @endphp
                @if($daysLeft !== null && $daysLeft < 0)
                    <span class="badge badge-expired">Expired</span>
                @elseif($daysLeft !== null && $daysLeft <= 7)
                    <span class="badge badge-expiring">Expiring Soon</span>
                @else
                    <span class="badge badge-active">Active</span>
                @endif
            @else
                <span class="badge badge-none">No Membership</span>
            @endif
        </div>

        <div class="member-id">{{ $memberId }}</div>

        @if($user->activeMembership)
        <div class="meta-strip">
            <div class="meta-item">
                <div class="meta-label">Plan</div>
                <div class="meta-value">{{ $membership->plan?->name ?? '—' }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Expires</div>
                <div class="meta-value">{{ $membership->expires_at?->format('M j, Y') ?? '—' }}</div>
            </div>
        </div>
        @endif

        <div class="qr-section">
            <div class="qr-wrapper">{!! $qrSvg !!}</div>
            <div class="verify-note">Scan to verify membership</div>
        </div>

    </div>
</div>
</body>
</html>