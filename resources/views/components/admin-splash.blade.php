@props([
    'target' => '',
    'successMessage' => session('success'),
    'resetFlag' => null
])

{{-- =========================
    LOADING OVERLAY
========================= --}}
@if($target)
<div
    wire:loading.flex
    wire:target="{{ $target }}"
    class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-black/90 backdrop-blur-xl"
>

    {{-- Glow Effect --}}
    <div class="relative flex items-center justify-center">

        <div class="absolute w-28 h-28 rounded-full bg-amber-500/20 blur-3xl animate-pulse"></div>

        {{-- Spinner --}}
        <svg
            class="relative w-16 h-16 text-amber-400 animate-spin"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle
                class="opacity-20"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
            ></circle>

            <path
                class="opacity-90"
                fill="currentColor"
                d="M12 2a10 10 0 00-9.95 9H6a6 6 0 016-6V2z"
            ></path>
        </svg>
    </div>

    {{-- Loading Text --}}
    <div class="mt-6 text-3xl font-extrabold tracking-wide text-white">
        Processing...
    </div>

    <p class="mt-2 text-sm font-semibold tracking-wide text-gray-400">
        Please wait while we complete your request
    </p>

</div>
@endif


{{-- =========================
    SUCCESS MODAL
========================= --}}
@if($successMessage)

<style>
    .success-gradient {
        background-size: 200% 200%;
        animation: gradientMove 5s ease infinite;
    }

    @keyframes gradientMove {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }
</style>

<div
    x-data="{ show: true }"
    x-show="show"
    x-init="
        setTimeout(() => {
            show = false;
            {{ $resetFlag ? '$wire.set(\''.$resetFlag.'\', false)' : '' }}
        }, 2500)
    "
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/90 backdrop-blur-xl"
>

    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-400"
        x-transition:enter-start="opacity-0 scale-75"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative w-full max-w-md"
    >

        {{-- Animated Green Border --}}
        <div class="absolute -inset-[2px] rounded-3xl bg-gradient-to-r from-green-400 via-emerald-500 to-teal-500 success-gradient blur-sm opacity-90"></div>

        {{-- Main Card --}}
        <div class="relative overflow-hidden rounded-3xl border border-green-500/20 bg-black p-8 shadow-[0_0_50px_rgba(34,197,94,0.15)]">

            {{-- Background Glow --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-green-500/10 rounded-full blur-3xl"></div>

            {{-- Success Icon --}}
            <div class="relative flex items-center justify-center w-24 h-24 mx-auto mb-6 rounded-full bg-green-500/10 border border-green-400/30 shadow-[0_0_30px_rgba(34,197,94,0.25)]">

                <svg
                    class="w-12 h-12 text-green-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.8"
                        d="M5 13l4 4L19 7"
                    />
                </svg>
            </div>

            {{-- Success Title --}}
        <div class="text-3xl font-extrabold tracking-wide text-center text-green-400">
                Success!
        </div>

            {{-- Success Message --}}
            <p class="mt-4 text-center text-gray-300 leading-relaxed">

                {{-- Bold Attributes/Text --}}
                <span class="font-bold text-white">
                    {{ $successMessage }}
                </span>

            </p>

        </div>
    </div>
</div>
@endif