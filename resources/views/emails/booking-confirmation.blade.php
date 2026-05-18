<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed — AmigosFitnessGym</title>
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

        /* Gold bar accent */
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
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.01em;
            margin: 0 0 8px;
        }

        .greeting {
            font-size: 14px;
            color: #888;
            margin: 0 0 28px;
            line-height: 1.6;
        }

        /* Details card */
        .details-card {
            background: #111;
            border: 1px solid #222;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 28px;
        }
        .details-card__label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #555;
            padding: 14px 20px 12px;
            border-bottom: 1px solid #1e1e1e;
        }
        .detail-row {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid #1a1a1a;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-icon {
            width: 32px;
            height: 32px;
            background: #1a1a1a;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
            flex-shrink: 0;
        }
        .detail-icon svg { display: block; }
        .detail-key {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #555;
            margin-bottom: 3px;
        }
        .detail-val {
            font-size: 14px;
            color: #ddd;
            font-weight: 600;
        }

        /* Sign-off */
        .signoff {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
            margin: 0;
        }
        .signoff strong { color: #fbbf24; }

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
        .footer-note { font-size: 11px; color: #333; }
    </style>
</head>
<body>
    <div class="wrapper">

        <div class="header">
            <div class="brand"><span>Amigos</span>FitnessGym</div>
        </div>

        <div class="gold-bar"></div>

        <div class="body">

            <div class="tag">Booking Confirmed</div>

            <h1 class="heading">You're all set!</h1>
            <p class="greeting">Hi <strong style="color:#ddd;">{{ $booking->member->name }}</strong>, your coaching session has been confirmed. See the details below.</p>

            <div class="details-card">
                <div class="details-card__label">Session Details</div>

                <div class="detail-row">
                    <div class="detail-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="detail-key">Coach</div>
                        <div class="detail-val">{{ $booking->coach->name }}</div>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div>
                        <div class="detail-key">Date &amp; Time</div>
                        <div class="detail-val">{{ $booking->scheduled_at->format('F j, Y \a\t g:i A') }}</div>
                    </div>
                </div>
            </div>

            <p class="signoff">See you at the gym! Train hard and <strong>stay consistent</strong>.</p>

        </div>

        <div class="footer">
            <div class="footer-brand">— <span>Amigos</span>FitnessGym</div>
            <div class="footer-note">&copy; {{ date('Y') }} AmigosFitnessGym</div>
        </div>

    </div>
</body>
</html>