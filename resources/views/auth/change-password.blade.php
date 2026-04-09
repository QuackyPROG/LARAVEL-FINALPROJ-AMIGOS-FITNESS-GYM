<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Set Your Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>

<div>
    <div>
        <h1>AmigosFitnessGym</h1>
    </div>

    <div>
        <h2>Set your password</h2>
        <p>You must set a new password before continuing.</p>

        @if ($errors->any())
            <div>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.change.update') }}">
            @csrf

            <div>
                <label>New password</label>
                <input
                    type="password"
                    name="password"
                    required
                    minlength="8"
                    placeholder="At least 8 characters"
                >
            </div>

            <div>
                <label>Confirm password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    placeholder="Repeat password"
                >
            </div>

            <button type="submit">Set Password &amp; Continue</button>
        </form>
    </div>
</div>

    @fluxScripts
    @livewireScripts
</body>
</html>
