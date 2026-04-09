<!DOCTYPE html>
<html><body style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px">
<h2>Booking Confirmed!</h2>
<p>Hi {{ $booking->member->name }},</p>
<p>Your coaching session has been confirmed.</p>
<table style="border-collapse:collapse;width:100%">
    <tr><td style="padding:8px;border:1px solid #eee"><strong>Coach</strong></td><td style="padding:8px;border:1px solid #eee">{{ $booking->coach->name }}</td></tr>
    <tr><td style="padding:8px;border:1px solid #eee"><strong>Date & Time</strong></td><td style="padding:8px;border:1px solid #eee">{{ $booking->scheduled_at->format('F j, Y \a\t g:i A') }}</td></tr>
</table>
<p style="margin-top:20px">See you at the gym!</p>
<p><em>AmigosFitnessGym</em></p>
</body></html>
