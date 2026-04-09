<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your membership is expiring soon</title>
    <style>
        body { font-family: Inter, Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; }
        .header { background: #0D0D0D; padding: 32px 40px; }
        .header .brand { font-size: 22px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; }
        .header .brand span { color: #E8400C; }
        .body { padding: 40px; color: #1a1a1a; }
        .body h1 { font-size: 24px; font-weight: 700; margin: 0 0 12px; }
        .body p { font-size: 15px; line-height: 1.6; color: #444; margin: 0 0 16px; }
        .badge { display: inline-block; background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A;
                 font-size: 13px; font-weight: 600; padding: 4px 12px; border-radius: 9999px; margin-bottom: 24px; }
        .cta { display: inline-block; background: #E8400C; color: #ffffff; font-size: 15px; font-weight: 700;
               text-decoration: none; padding: 14px 32px; border-radius: 8px; margin-top: 8px; }
        .footer { padding: 24px 40px; background: #f9f9f9; border-top: 1px solid #eee;
                  font-size: 12px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="brand"><span>Amigos</span>FitnessGym</div>
        </div>
        <div class="body">
            <div class="badge">Expiring Soon</div>
            <h1>Hey {{ $member->name }}, your membership expires soon!</h1>
            <p>
                Your <strong>{{ $membership->plan->name }}</strong> membership expires on
                <strong>{{ $membership->expires_at->format('F j, Y') }}</strong> —
                that's in {{ now()->diffInDays($membership->expires_at) }} day(s).
            </p>
            <p>
                Don't lose access to the gym. Renew now and keep your fitness momentum going strong.
            </p>
            <a href="{{ url('/register') }}" class="cta">Renew Now →</a>
            <p style="margin-top: 24px; font-size: 13px; color: #888;">
                If you've already renewed, you can ignore this email.
            </p>
        </div>
        <div class="footer">
            AmigosFitnessGym · 123 Fitness Street, Makati City, Metro Manila<br>
            You're receiving this because your membership is expiring soon.
        </div>
    </div>
</body>
</html>
