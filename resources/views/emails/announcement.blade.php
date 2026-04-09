<!DOCTYPE html>
<html><body style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px">
<h2>{{ $announcement->subject }}</h2>
<div>{!! nl2br(e($announcement->body)) !!}</div>
<p style="margin-top:20px;color:#666;font-size:12px">— AmigosFitnessGym Team</p>
</body></html>
