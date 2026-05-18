<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to AmigosFitnessGym</title>
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
        .header-sub {
            font-size: 12px;
            color: #555;
            margin-top: 4px;
            letter-spacing: 0.05em;
        }

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
            color: #000;
            background: #fbbf24;
            border-radius: 4px;
            padding: 4px 10px;
            margin-bottom: 20px;
        }

        .heading {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.01em;
            margin: 0 0 10px;
        }

        .greeting {
            font-size: 14px;
            color: #888;
            line-height: 1.75;
            margin: 0 0 28px;
        }
        .greeting strong { color: #ddd; }

        /* Section label */
        .section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #555;
            margin-bottom: 10px;
        }

        /* Credentials card */
        .card {
            background: #111;
            border: 1px solid #222;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid #1a1a1a;
        }
        .card-row:last-child { border-bottom: none; }
        .card-key {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #555;
        }
        .card-val {
            font-size: 13px;
            color: #ddd;
            font-weight: 600;
            font-family: 'Courier New', monospace;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 4px;
            padding: 4px 10px;
            max-width: 60%;
            word-break: break-all;
            text-align: right;
        }

        /* Notice */
        .notice {
            background: #111;
            border: 1px solid #2a2a2a;
            border-left: 3px solid #fbbf24;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #888;
            line-height: 1.6;
        }
        .notice strong { color: #fbbf24; }

        /* Plan card */
        .plan-card {
            background: #111;
            border: 1px solid #222;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 28px;
        }
        .plan-row {
            padding: 14px 20px;
            border-bottom: 1px solid #1a1a1a;
        }
        .plan-row:last-child { border-bottom: none; }
        .plan-row-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #555;
            margin-bottom: 4px;
        }
        .plan-row-val {
            font-size: 14px;
            color: #ddd;
            font-weight: 600;
        }

        /* CTA */
        .cta-wrap { text-align: center; margin-bottom: 28px; }
        .cta {
            display: inline-block;
            background: #fbbf24;
            color: #000;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            padding: 13px 32px;
            border-radius: 6px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .help-text {
            font-size: 12px;
            color: #444;
            text-align: center;
            margin: 0;
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
        .footer-link {
            font-size: 11px;
            color: #333;
            text-decoration: none;
        }
        .footer-link:hover { color: #fbbf24; }
    </style>
</head>
<body>
    <div class="wrapper">

        <div class="header">
            <div class="brand"><span>Amigos</span>FitnessGym</div>
            <div class="header-sub">Welcome — you're officially a member.</div>
        </div>

        <div class="gold-bar"></div>

        <div class="body">

            <div class="tag">New Member</div>

            <h1 class="heading">Your account is ready, {{ $memberName }}.</h1>
            <p class="greeting">Your payment was confirmed. Use the credentials below to log in and set your password on first sign-in.</p>

            <div class="section-label">Your Login Details</div>
            <div class="card">
                <div class="card-row">
                    <div class="card-key">Email</div>
                    <div class="card-val">{{ $email }}</div>
                </div>
                <div class="card-row">
                    <div class="card-key">Temporary Password</div>
                    <div class="card-val">{{ $tempPassword }}</div>
                </div>
            </div>

            <div class="notice">
                <strong>First Login:</strong> You'll be prompted to set a new password immediately after signing in. Keep this email until you've done that.
            </div>

            @if($planName || $expiryDate)
            <div class="section-label">Your Membership</div>
            <div class="plan-card">
                @if($planName)
                <div class="plan-row">
                    <div class="plan-row-label">Plan</div>
                    <div class="plan-row-val">{{ $planName }}</div>
                </div>
                @endif
                @if($expiryDate)
                <div class="plan-row">
                    <div class="plan-row-label">Valid Until</div>
                    <div class="plan-row-val">{{ $expiryDate }}</div>
                </div>
                @endif
            </div>
            @endif

            <div class="cta-wrap">
                <a href="{{ $loginUrl }}" class="cta">Login to Your Portal →</a>
            </div>

            <p class="help-text">Questions? Visit us at the gym or reply to this email.</p>

        </div>

        <div class="footer">
            <div class="footer-brand">&copy; {{ date('Y') }} <span>Amigos</span>FitnessGym</div>
            <a href="{{ $loginUrl }}" class="footer-link">{{ $loginUrl }}</a>
        </div>

    </div>
</body>
</html>