<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to AmigosFitnessGym</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f4f4; font-family: 'Inter', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #E8400C; padding: 32px 40px; }
        .header h1 { margin: 0; color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: 0.5px; }
        .header p { margin: 4px 0 0; color: rgba(255,255,255,0.85); font-size: 13px; }
        .body { padding: 40px; }
        .greeting { font-size: 16px; color: #1a1a1a; margin-bottom: 24px; }
        .section-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: #8a8a8a; margin-bottom: 12px; }
        .credentials-box { background: #f8f8f8; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px 24px; margin-bottom: 24px; }
        .credential-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .credential-row:last-child { margin-bottom: 0; }
        .credential-label { font-size: 12px; color: #8a8a8a; font-weight: 500; }
        .credential-value { font-family: 'JetBrains Mono', 'Courier New', monospace; font-size: 14px; color: #1a1a1a; font-weight: 600; background: #ffffff; border: 1px solid #d0d0d0; border-radius: 4px; padding: 4px 10px; }
        .notice { background: #fff8f0; border: 1px solid #fde8cc; border-radius: 8px; padding: 16px 20px; margin-bottom: 24px; font-size: 13px; color: #7a4f00; }
        .notice strong { color: #c45a00; }
        .plan-box { background: #f8f8f8; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px 24px; margin-bottom: 32px; }
        .plan-row { font-size: 14px; color: #333; margin-bottom: 8px; }
        .plan-row:last-child { margin-bottom: 0; }
        .plan-row span { color: #8a8a8a; font-size: 12px; display: block; margin-bottom: 2px; }
        .cta { text-align: center; margin-bottom: 32px; }
        .cta a { display: inline-block; background: #E8400C; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 15px; padding: 14px 36px; border-radius: 8px; }
        .footer { border-top: 1px solid #e8e8e8; padding: 24px 40px; font-size: 12px; color: #aaa; text-align: center; }
        .footer a { color: #E8400C; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>AmigosFitnessGym</h1>
        <p>Welcome — you're officially a member.</p>
    </div>

    <div class="body">
        <p class="greeting">Hi <strong>{{ $memberName }}</strong>, your payment was confirmed and your account is ready. Here are your login credentials.</p>

        <div class="section-label">Your Login Details</div>
        <div class="credentials-box">
            <div class="credential-row">
                <span class="credential-label">Email</span>
                <span class="credential-value">{{ $email }}</span>
            </div>
            <div class="credential-row">
                <span class="credential-label">Temporary Password</span>
                <span class="credential-value">{{ $tempPassword }}</span>
            </div>
        </div>

        <div class="notice">
            <strong>First Login:</strong> You will be asked to set a new password immediately after logging in. Keep this email until you've done that.
        </div>

        @if($planName || $expiryDate)
        <div class="section-label">Your Membership</div>
        <div class="plan-box">
            @if($planName)
            <div class="plan-row">
                <span>Plan</span>
                {{ $planName }}
            </div>
            @endif
            @if($expiryDate)
            <div class="plan-row">
                <span>Valid Until</span>
                {{ $expiryDate }}
            </div>
            @endif
        </div>
        @endif

        <div class="cta">
            <a href="{{ $loginUrl }}">Login to Your Portal</a>
        </div>

        <p style="font-size: 13px; color: #888; text-align: center;">If you have questions, visit us at the gym or reply to this email.</p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} AmigosFitnessGym. All rights reserved.<br>
        <a href="{{ $loginUrl }}">{{ $loginUrl }}</a>
    </div>
</div>
</body>
</html>
