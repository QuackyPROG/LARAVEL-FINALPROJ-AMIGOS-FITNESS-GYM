@extends('layouts.portal')

@section('title', 'Support')

@section('content')
<div>

    <div>
        <h1>Support</h1>
        <p>We're here to help</p>
    </div>

    <div>
        <div>
            <h2>Chat with us</h2>
            <p>
                Use the <span>chat widget</span> in the bottom-right corner to start a conversation.
                Our AI assistant answers instantly — if you need a human, just type
                <span>agent</span>
                and we'll connect you with support staff.
            </p>
        </div>
    </div>

    <div>
        <h2>Frequently Asked Questions</h2>

        <div x-data="{ open: null }">

            @php
            $faqs = [
                [1, 'How do I renew my membership?',
                    'Click <strong>Renew Membership</strong> on your dashboard when your membership is expiring soon or has expired. You can also visit the registration page and select a plan to start a new subscription.'],
                [2, 'What are your gym hours?',
                    '<strong>Monday – Friday:</strong> 5:00 AM – 10:00 PM<br><strong>Saturday – Sunday:</strong> 6:00 AM – 8:00 PM'],
                [3, 'How do I cancel a coaching session?',
                    'Cancellations are allowed up to <strong>24 hours before</strong> your session. After that, the booking is locked. Visit the Coaches page to manage your bookings.'],
                [4, 'How do I download my membership card?',
                    'Go to <strong>My Card</strong> in the sidebar, then click <strong>Download PDF</strong>. Your digital card includes a QR code that gym staff can scan to verify your membership.'],
                [5, 'Who do I contact for billing issues?',
                    'Use the live chat widget in the bottom-right corner or email us at <a href="mailto:support@amigosfitness.ph">support@amigosfitness.ph</a>. We aim to respond within 1 business day.'],
            ];
            @endphp

            @foreach($faqs as [$id, $question, $answer])
            <div>
                <button @click="open = open === {{ $id }} ? null : {{ $id }}">
                    <span>{{ $question }}</span>
                    <svg width="16" height="16"
                         :class="open === {{ $id }} ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === {{ $id }}"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <p>{!! $answer !!}</p>
                </div>
            </div>
            @endforeach

        </div>
    </div>

    <div>
        <h2>Other ways to reach us</h2>
        <div>
            <a href="mailto:support@amigosfitness.ph">
                <div>
                    <p>Email</p>
                    <p>support@amigosfitness.ph</p>
                </div>
            </a>
            <div>
                <div>
                    <p>Response time</p>
                    <p>Within 1 business day</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
