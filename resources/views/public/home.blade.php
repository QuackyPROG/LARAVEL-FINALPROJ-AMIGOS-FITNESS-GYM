@extends('layouts.public')

@section('title', 'Welcome')

@section('content')

    {{-- ===== HERO ===== --}}
    <section class="relative overflow-hidden border-b border-gray-100 py-16">
        @if($content['hero_image'])
            <div
                style="background-image: url('{{ asset('storage/' . $content['hero_image']) }}');"
                aria-hidden="true"
                class="absolute inset-0 bg-cover bg-center bg-gray-200"
            ></div>
        @else
            <div aria-hidden="true" class="absolute inset-0 bg-gray-100"></div>
        @endif

        <div class="max-w-5xl mx-auto px-6 relative">
            <div class="max-w-2xl">
                <h1 class="text-4xl font-semibold text-gray-900 mb-4">{{ $content['hero_title'] }}</h1>
                <p class="text-lg text-gray-600 mb-6">{{ $content['hero_subtitle'] }}</p>
                @guest
                    <a href="{{ route('register') }}" class="inline-block bg-gray-900 text-white px-6 py-3 rounded-md text-sm">Become a Member</a>
                @else
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}" class="inline-block border border-gray-300 text-gray-700 px-6 py-3 rounded-md text-sm">
                        Go to My Dashboard &rarr;
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- ===== PLANS ===== --}}
    <section id="plans" class="py-16 border-b border-gray-100 bg-gray-50">
        <div class="max-w-5xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-1">Membership</p>
                <h2 class="text-2xl font-semibold text-gray-900">Choose Your Plan</h2>
                <p class="text-gray-500 mt-1">Flexible options designed for every training goal and schedule.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($plans as $plan)
                    <div class="bg-white border border-gray-200 rounded-md p-6" data-plan-name="{{ $plan->name }}">
                        <div class="mb-4">
                            <h3 class="font-semibold text-gray-900">{{ $plan->name }}</h3>
                            <p class="text-sm text-gray-400">{{ $plan->duration_days }}-Day Access</p>
                        </div>

                        <div class="mb-4">
                            <span class="text-2xl font-semibold text-gray-900">₱{{ number_format($plan->price, 0) }}</span>
                            <span class="text-sm text-gray-400"> / {{ $plan->duration_days }} days</span>
                        </div>

                        <ul class="text-sm text-gray-600 space-y-1 mb-6 list-disc pl-4">
                            @foreach(($plan->benefits ?? []) as $benefit)
                                <li>{{ $benefit }}</li>
                            @endforeach
                        </ul>

                        @guest
                            <a href="{{ route('register', ['plan' => $plan->id]) }}" class="block text-center bg-gray-900 text-white text-sm px-4 py-2 rounded-md">Get Started</a>
                        @else
                            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}" class="block text-center border border-gray-300 text-gray-700 text-sm px-4 py-2 rounded-md">
                                Go to Dashboard &rarr;
                            </a>
                        @endguest
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== COACHES ===== --}}
    @if($coaches->isNotEmpty())
    <section id="coaches" class="py-16 border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-1">Our Team</p>
                <h2 class="text-2xl font-semibold text-gray-900">Meet Your Coaches</h2>
                <p class="text-gray-500 mt-1">World-class coaches dedicated to helping you reach your peak performance.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($coaches as $coach)
                    <div class="bg-white border border-gray-200 rounded-md p-5" data-coach-name="{{ $coach->name }}">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3 border border-gray-200">
                            @if($coach->photo)
                                <img
                                    src="{{ asset('storage/' . $coach->photo) }}"
                                    alt="{{ $coach->name }}"
                                    class="w-12 h-12 rounded-full object-cover"
                                >
                            @else
                                <span class="text-lg font-semibold text-gray-400">{{ strtoupper(substr($coach->name, 0, 1)) }}</span>
                            @endif
                        </div>

                        <h3 class="font-semibold text-gray-900">{{ $coach->name }}</h3>

                        <div class="flex flex-wrap gap-1 mt-2 mb-2">
                            @foreach(($coach->specializations ?? []) as $spec)
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">{{ $spec }}</span>
                            @endforeach
                        </div>

                        <p class="text-sm text-gray-500">{{ $coach->bio }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== GYM INFO ===== --}}
    <section id="contact" class="py-16">
        <div class="max-w-5xl mx-auto px-6">
            <div class="mb-8">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-1">Find Us</p>
                <h2 class="text-2xl font-semibold text-gray-900">Visit AmigosFitnessGym</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white border border-gray-200 rounded-md p-4">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Hours</h4>
                    <p class="text-sm text-gray-700">{{ $content['gym_hours'] }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-md p-4">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Address</h4>
                    <p class="text-sm text-gray-700">{{ $content['gym_address'] }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-md p-4">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Phone</h4>
                    <p class="text-sm text-gray-700">{{ $content['gym_phone'] }}</p>
                </div>
            </div>

            <div>
                @guest
                    <a href="{{ route('register') }}" class="inline-block bg-gray-900 text-white text-sm px-6 py-3 rounded-md">Join Now — Become a Member</a>
                @else
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}" class="inline-block border border-gray-300 text-gray-700 text-sm px-6 py-3 rounded-md">
                        Go to My Dashboard &rarr;
                    </a>
                @endguest
            </div>
        </div>
    </section>

@endsection
