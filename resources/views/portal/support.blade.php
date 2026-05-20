@extends('layouts.portal')

@section('title', 'Support')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Support</h1>
        <p class="text-gray-300">Get help with memberships, bookings, billing, and gym access</p>
    </div>

    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-white/10 bg-white/5 flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-400/10 text-amber-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5m8-2a9 9 0 11-4.2-7.6L21 4l-1.1 4.1A8.9 8.9 0 0121 12Z"/></svg>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-white">Chat With Us</h2>
                <p class="text-xs text-gray-400 mt-0.5">Fast help, right here in your portal</p>
            </div>
        </div>
        <div class="px-5 py-4">
            <p class="text-sm text-gray-300 leading-6">
                Use the <span class="font-semibold text-white">chat widget</span> in the bottom-right corner to start a conversation.
                Our AI assistant answers instantly. If you need a human, type <code class="bg-white/5 border border-white/10 text-amber-400 px-1.5 py-0.5 rounded text-xs font-bold">agent</code>
                and we will connect you with support staff.
            </p>
        </div>
    </div>

    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-white/10 bg-white/5">
            <h2 class="text-xs font-bold text-amber-400 uppercase tracking-widest">Frequently Asked Questions</h2>
        </div>

        <div x-data="{ open: null }">


            @php
            $faqs = [
                [1, 'How do I renew my membership?',
                    'Click <strong>Renew Membership</strong> on your dashboard when your membership is expiring soon or has expired. You can also visit the registration page and select a plan to start a new subscription.'],
                [2, 'What are your gym hours?',
                    '<strong>Monday - Friday:</strong> 5:00 AM - 10:00 PM<br><strong>Saturday - Sunday:</strong> 6:00 AM - 8:00 PM'],
                [3, 'How do I cancel a coaching session?',
                    'Cancellations are allowed up to <strong>24 hours before</strong> your session. After that, the booking is locked. Visit the Coaches page to manage your bookings.'],
                [4, 'How do I download my membership card?',
                    'Go to <strong>My Card</strong> in the sidebar, then click <strong>Download PDF</strong>. Your digital card includes a QR code that gym staff can scan to verify your membership.'],
                [5, 'Who do I contact for billing issues?',
                    'Use the live chat widget in the bottom-right corner or email us at <a class="font-bold text-amber-400 underline underline-offset-2 hover:text-amber-300" href="mailto:support@amigosfitness.ph">support@amigosfitness.ph</a>. We aim to respond within 1 business day.'],
            ];
            @endphp

            @foreach($faqs as [$id, $question, $answer])
            <div>
                <button @click="open = open === {{ $id }} ? null : {{ $id }}" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left transition hover:bg-amber-400/5 sm:px-6">
                    <span class="text-sm font-semibold text-white">{{ $question }}</span>
                    <svg width="16" height="16"
                         :class="open === {{ $id }} ? 'rotate-180 text-amber-400' : ''"
                         class="shrink-0 text-zinc-500 transition"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === {{ $id }}"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="px-5 pb-5 sm:px-6">
                    <p class="text-sm leading-6 text-zinc-400">{!! $answer !!}</p>
                </div>
            </div>
            @endforeach

        </div>
    </div>

    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-white/10 bg-white/5">
            <h2 class="text-xs font-bold text-amber-400 uppercase tracking-widest">Other Ways To Reach Us</h2>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 p-5">
            <div class="bg-white/5 border border-white/10 rounded-lg p-5">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-amber-400/10 text-amber-400 border border-amber-500/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Email</p>
                <p class="mt-1 mb-4 text-sm font-semibold text-white">support@amigosfitness.ph</p>
                <a href="mailto:support@amigosfitness.ph" class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 text-black font-bold text-sm px-5 py-2 rounded-xl transition-all transform hover:-translate-y-0.5">
                    Send Email
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-lg p-5">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-gray-400 border border-white/10">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Response Time</p>
                <p class="mt-1 text-sm font-semibold text-white">Within 1 business day</p>
                <p class="mt-2 text-xs text-gray-500 leading-5">Our team reviews all inquiries Monday through Friday. Urgent gym access issues can be resolved via chat support.</p>
            </div>
        </div>
    </div>
</div>
@endsection