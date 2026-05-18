<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your membership is expiring soon — AmigosFitnessGym</title>
    <style>
        body { margin: 0; padding: 0; background: #0a0a0a; font-family: Arial, sans-serif; }

        .wrapper {
            max-width: 580px;
            margin: 40px auto;
            background: #0d0d0d;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #1a1a1a;
        }

        /* Header */
        .header {
            background: #000;
            padding: 28px 40px;
            border-bottom: 1px solid rgba(251,191,36,0.12);
        }
        .brand {
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #fff;
        }
        .brand span { color: #fbbf24; }

        /* Gold bar */
        .gold-bar {
            height: 3px;
            background: linear-gradient(90deg, #fbbf24, #f59e0b, transparent);
        }

        /* Body */
        .body { padding: 36px 40px; }

        .tag {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #92400e;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 4px;
            padding: 4px 10px;
            margin-bottom: 20px;
        }

        .heading {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.01em;
            line-height: 1.3;
            margin: 0 0 14px;
        }

        .body-text {
            font-size: 14px;
            color: #888;
            line-height: 1.75;
            margin: 0 0 14px;
        }
        .body-text strong { color: #ddd; }

        /* Countdown card */
        .countdown-card {
            background: #111;
            border: 1px solid #222;
            border-left: 3px solid #fbbf24;
            border-radius: 8px;
            padding: 18px 22px;
            margin: 24px 0;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .countdown-icon {
            width: 40px;
            height: 40px;
            background: rgba(251,191,36,0.08);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .countdown-text {
            font-size: 13px;
            color: #888;
            line-height: 1.5;
        }
        .countdown-text strong { color: #fbbf24; font-size: 15px; }

        /* CTA button */
        .cta-wrap { margin: 28px 0 20px; }
        .cta {
            display: inline-block;
            background: #fbbf24;
            color: #000;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            padding: 13px 28px;
            border-radius: 6px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .note {
            font-size: 12px;
            color: #444;
            margin: 16px 0 0;
            line-height: 1.6;
        }

        /* Footer */
        .footer {
            background: #000;
            border-top: 1px solid #1a1a1a;
            padding: 22px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .footer-brand {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #444;
        }
        .footer-brand span { color: #fbbf24; }
        .footer-addr { font-size: 11px; color: #333; text-align: right; }
    </style>
</head>
<body>
    <div class="wrapper">

        <div class="header">
            <div class="brand"><span>Amigos</span>FitnessGym</div>
        </div>

        <div class="gold-bar"></div>

        <div class="body">

            <div class="tag">Expiring Soon</div>

            <h1 class="heading">Hey {{ $member->name }}, your membership expires soon!</h1>

            <p class="body-text">
                Your <strong>{{ $membership->plan->name }}</strong> membership is expiring on
                <strong>{{ $membership->expires_at->format('F j, Y') }}</strong>.
                Don't lose your momentum — renew now and keep your access uninterrupted.
            </p>

            <div class="countdown-card">
                <div class="countdown-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <div class="countdown-text">
                    <strong>{{ now()->diffInDays($membership->expires_at) }} day(s) remaining</strong><br>
                    Your membership ends {{ $membership->expires_at->format('F j, Y') }}.
                </div>
            </div>

            <div class="cta-wrap">
                <a href="{{ url('/register') }}" class="cta">Renew Now →</a>
            </div>

            <p class="note">If you've already renewed, you can safely ignore this email.</p>

        </div>

        <div class="footer">
            <div class="footer-brand">— <span>Amigos</span>FitnessGym</div>
            <div class="footer-addr">123 Fitness Street, Makati City, Metro Manila</div>
        </div>

    </div>
</body>
</html>