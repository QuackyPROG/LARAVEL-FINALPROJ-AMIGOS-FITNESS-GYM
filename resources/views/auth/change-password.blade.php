<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Set Your Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-gray-900">AmigosFitnessGym</h1>
        </div>

        <div class="bg-white border border-gray-200 rounded-md p-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Set your password</h2>
            <p class="text-sm text-gray-500 mb-6">You must set a new password before continuing.</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-md mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.change.update') }}">
                @csrf

                <div class="flex flex-col gap-1 mb-4">
                    <label class="text-sm font-medium text-gray-700">New password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        minlength="8"
                        placeholder="At least 8 characters"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full"
                    >
                </div>

                <div class="flex flex-col gap-1 mb-6">
                    <label class="text-sm font-medium text-gray-700">Confirm password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        placeholder="Repeat password"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full"
                    >
                </div>

                <button type="submit" class="w-full bg-gray-900 text-white text-sm px-4 py-2 rounded-md">Set Password &amp; Continue</button>
            </form>
        </div>

    </div>
</div>

    @fluxScripts
    @livewireScripts
</body>
</html>
