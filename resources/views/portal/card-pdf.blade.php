<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Membership Card — {{ $user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            padding: 40px;
        }
        .card {
            width: 400px;
            background: linear-gradient(135deg, #1A1A1A 0%, #0D0D0D 100%);
            border-radius: 16px;
            overflow: hidden;
        }
        .stripe {
            height: 4px;
            background: #E8400C;
        }
        .card-body {
            padding: 32px;
        }
        .label {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #555;
            margin-bottom: 4px;
        }
        .gym-name {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #555;
            margin-bottom: 8px;
        }
        .member-name {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
        }
        .member-id {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #666;
        }
        .divider {
            border-top: 1px solid rgba(255,255,255,0.08);
            margin: 20px 0;
        }
        .info-grid {
            display: flex;
            gap: 32px;
            margin-bottom: 20px;
        }
        .info-item {}
        .info-value {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
        }
        .badge-active { background: rgba(34,197,94,0.2); color: #4ade80; }
        .badge-expiring { background: rgba(245,158,11,0.2); color: #fbbf24; }
        .badge-expired { background: rgba(239,68,68,0.2); color: #f87171; }
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .qr-section {
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.08);
            padding-top: 20px;
        }
        .qr-wrapper {
            display: inline-block;
            background: white;
            padding: 12px;
            border-radius: 10px;
        }
        .verify-note {
            font-size: 9px;
            color: #444;
            margin-top: 8px;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="stripe"></div>
    <div class="card-body">
        <div class="header-row">
            <div>
                <div class="gym-name">Amigos Fitness Gym</div>
                <div class="member-name">{{ $user->name }}</div>
                <div class="member-id">{{ $memberId }}</div>
            </div>
            <div>
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
                    <span class="badge badge-expired">No Membership</span>
                @endif
            </div>
        </div>

        @if($user->activeMembership)
        <div class="divider"></div>
        <div class="info-grid">
            <div class="info-item">
                <div class="label">Plan</div>
                <div class="info-value">{{ $user->activeMembership->plan?->name ?? '—' }}</div>
            </div>
            <div class="info-item">
                <div class="label">Expires</div>
                <div class="info-value">{{ $user->activeMembership->expires_at?->format('M j, Y') ?? '—' }}</div>
            </div>
        </div>
        @endif

        <div class="qr-section">
            <div class="qr-wrapper">
                {!! $qrSvg !!}
            </div>
            <div class="verify-note">Scan to verify membership</div>
        </div>
    </div>
</div>
</body>
</html>
