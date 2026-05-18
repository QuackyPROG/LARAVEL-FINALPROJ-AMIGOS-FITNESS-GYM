<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->subject }}</title>
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
            display: flex;
            align-items: center;
            gap: 10px;
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

        /* Tag */
        .tag-row { padding: 28px 40px 0; }
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
        }

        /* Body */
        .body { padding: 20px 40px 36px; }
        .subject {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.01em;
            line-height: 1.3;
            margin: 0 0 20px;
        }
        .divider {
            height: 1px;
            background: #1e1e1e;
            margin-bottom: 20px;
        }
        .content {
            font-size: 14px;
            line-height: 1.75;
            color: #aaa;
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
        .footer-note {
            font-size: 11px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="wrapper">

        <div class="header">
            <div class="brand"><span>Amigos</span>FitnessGym</div>
        </div>

        <div class="gold-bar"></div>

        <div class="tag-row">
            <span class="tag">Announcement</span>
        </div>

        <div class="body">
            <h1 class="subject">{{ $announcement->subject }}</h1>
            <div class="divider"></div>
            <div class="content">{!! nl2br(e($announcement->body)) !!}</div>
        </div>

        <div class="footer">
            <div class="footer-brand">— <span>Amigos</span>FitnessGym Team</div>
            <div class="footer-note">&copy; {{ date('Y') }} AmigosFitnessGym</div>
        </div>

    </div>
</body>
</html>