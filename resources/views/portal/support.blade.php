@extends('layouts.portal')

@section('title', 'Support')

@push('styles')
@endpush

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <section class="relative overflow-hidden rounded-lg bg-[#080808] px-5 py-6 ring-1 ring-amber-400/10 sm:px-7">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_15%,rgba(251,191,36,0.14),transparent_34%)]"></div>
        <div class="relative">
            <span class="inline-flex rounded-full border border-amber-400/25 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">
                Member Support
            </span>
            <h1 class="mt-4 text-4xl font-black uppercase leading-none text-white sm:text-5xl">Support</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-400">
                Get help with memberships, bookings, billing, and gym access without leaving your portal.
            </p>
        </div>
    </section>

    <section class="rounded-lg bg-[#0b0b0b] p-5 ring-1 ring-white/10 sm:p-6">
        <div class="grid gap-5 lg:grid-cols-[0.75fr_1.25fr] lg:items-center">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">Chat With Us</p>
                <h2 class="mt-2 text-xl font-black uppercase text-white">Fast Help, Same Page</h2>
            </div>
            <p class="text-sm leading-6 text-zinc-400">
                Use the <span class="font-semibold text-white">chat widget</span> in the bottom-right corner to start a conversation.
                Our AI assistant answers instantly. If you need a human, type <span class="font-semibold text-amber-400">agent</span>
                and we will connect you with support staff.
            </p>
        </div>
    </section>

    <section class="overflow-hidden rounded-lg bg-[#0b0b0b] ring-1 ring-white/10">
        <div class="border-b border-amber-400/10 px-5 py-4 sm:px-6">
            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">Frequently Asked Questions</p>
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
    </section>

    <section class="rounded-lg bg-[#0b0b0b] p-5 ring-1 ring-white/10 sm:p-6">
        <div class="mb-5">
            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-amber-400">Other Ways To Reach Us</p>
        </div>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div class="rounded-md bg-zinc-950/70 p-4 ring-1 ring-white/5">
                <p class="text-xs font-bold uppercase tracking-wide text-zinc-500">Email</p>
                <p class="mt-1 mb-3 text-sm font-semibold text-white">support@amigosfitness.ph</p>
                <a href="mailto:support@amigosfitness.ph"
                   style="display:inline-flex;align-items:center;justify-content:center;gap:6px;width:100%;background:#fbbf24;color:#000;font-size:13px;font-weight:700;letter-spacing:0.02em;padding:8px 18px;border-radius:6px;border:none;text-decoration:none;transition:background 0.2s,transform 0.2s,box-shadow 0.2s;cursor:pointer;"
                   onmouseover="this.style.background='#f59e0b';this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(251,191,36,0.3)'"
                   onmouseout="this.style.background='#fbbf24';this.style.transform='none';this.style.boxShadow='none'">
                    Send Email
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
            <div class="rounded-md bg-zinc-950/70 p-4 ring-1 ring-white/5">
                <p class="text-xs font-bold uppercase tracking-wide text-zinc-500">Response Time</p>
                <p class="mt-1 text-sm font-semibold text-white">Within 1 business day</p>
            </div>
        </div>
    </section>
</div>
@endsection