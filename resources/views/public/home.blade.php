@extends('layouts.public')

@section('title', 'Welcome')

@section('content')

    {{-- ===== HERO ===== --}}
    <section>
        @if($content['hero_image'])
            <div
                style="background-image: url('{{ asset('storage/' . $content['hero_image']) }}');"
                aria-hidden="true"
            ></div>
        @else
            <div aria-hidden="true"></div>
        @endif

        <div>
            <div>
                <h1>{{ $content['hero_title'] }}</h1>
                <p>{{ $content['hero_subtitle'] }}</p>
                @guest
                    <a href="{{ route('register') }}">Become a Member</a>
                @else
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}">
                        Go to My Dashboard &rarr;
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- ===== PLANS ===== --}}
    <section id="plans">
        <div>
            <div>
                <p>Membership</p>
                <h2>Choose Your Plan</h2>
                <p>Flexible options designed for every training goal and schedule.</p>
            </div>

            <div>
                @foreach($plans as $plan)
                    <div data-plan-name="{{ $plan->name }}">
                        <div>
                            <h3>{{ $plan->name }}</h3>
                            <p>{{ $plan->duration_days }}-Day Access</p>
                        </div>

                        <div>
                            <span>₱{{ number_format($plan->price, 0) }}</span>
                            <span>/ {{ $plan->duration_days }} days</span>
                        </div>

                        <ul>
                            @foreach(($plan->benefits ?? []) as $benefit)
                                <li>{{ $benefit }}</li>
                            @endforeach
                        </ul>

                        @guest
                            <a href="{{ route('register', ['plan' => $plan->id]) }}">Get Started</a>
                        @else
                            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}">
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
    <section id="coaches">
        <div>
            <div>
                <p>Our Team</p>
                <h2>Meet Your Coaches</h2>
                <p>World-class coaches dedicated to helping you reach your peak performance.</p>
            </div>

            <div>
                @foreach($coaches as $coach)
                    <div data-coach-name="{{ $coach->name }}">
                        <div>
                            @if($coach->photo)
                                <img
                                    src="{{ asset('storage/' . $coach->photo) }}"
                                    alt="{{ $coach->name }}"
                                >
                            @else
                                <span>{{ strtoupper(substr($coach->name, 0, 1)) }}</span>
                            @endif
                        </div>

                        <h3>{{ $coach->name }}</h3>

                        <div>
                            @foreach(($coach->specializations ?? []) as $spec)
                                <span>{{ $spec }}</span>
                            @endforeach
                        </div>

                        <p>{{ $coach->bio }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== GYM INFO ===== --}}
    <section id="contact">
        <div>
            <div>
                <p>Find Us</p>
                <h2>Visit AmigosFitnessGym</h2>
            </div>

            <div>
                <div>
                    <h4>Hours</h4>
                    <p>{{ $content['gym_hours'] }}</p>
                </div>

                <div>
                    <h4>Address</h4>
                    <p>{{ $content['gym_address'] }}</p>
                </div>

                <div>
                    <h4>Phone</h4>
                    <p>{{ $content['gym_phone'] }}</p>
                </div>
            </div>

            <div>
                @guest
                    <a href="{{ route('register') }}">Join Now — Become a Member</a>
                @else
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('portal.dashboard') }}">
                        Go to My Dashboard &rarr;
                    </a>
                @endguest
            </div>
        </div>
    </section>

@endsection
