<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>

<div>
    <div>
        <h1>AmigosFitnessGym</h1>
        <p>Member &amp; Admin Portal</p>
    </div>

    <div>
        <h2>Sign in</h2>

        @if ($errors->any())
            <div>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <div>
                <label>Email address</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="you@example.com"
                >
            </div>

            <div>
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    placeholder="••••••••"
                >
            </div>

            <div>
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit">Sign in</button>
        </form>
    </div>

    <p>
        Not a member yet?
        <a href="/register">Join AmigosFitnessGym</a>
    </p>
</div>

    @fluxScripts
    @livewireScripts
</body>
</html>
