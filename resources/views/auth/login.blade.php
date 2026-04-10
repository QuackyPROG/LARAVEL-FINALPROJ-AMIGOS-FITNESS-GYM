<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Sign In</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-gray-900">AmigosFitnessGym</h1>
            <p class="text-sm text-gray-500 mt-1">Member &amp; Admin Portal</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-md p-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Sign in</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-md mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf

                <div class="flex flex-col gap-1 mb-4">
                    <label class="text-sm font-medium text-gray-700">Email address</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="you@example.com"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full"
                    >
                </div>

                <div class="flex flex-col gap-1 mb-4">
                    <label class="text-sm font-medium text-gray-700">Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full"
                    >
                </div>

                <div class="flex items-center gap-2 mb-6">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" class="text-sm text-gray-600">Remember me</label>
                </div>

                <button type="submit" class="w-full bg-gray-900 text-white text-sm px-4 py-2 rounded-md">Sign in</button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-4">
            Not a member yet?
            <a href="/register" class="text-gray-900 underline">Join AmigosFitnessGym</a>
        </p>

    </div>
</div>

    @fluxScripts
    @livewireScripts
</body>
</html>
