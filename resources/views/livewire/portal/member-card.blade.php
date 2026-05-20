<div>
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">My Card</h1>
        <p class="text-gray-300">Your digital membership pass — show this at the front desk or scan the QR code</p>
    </div>

    {{-- Membership Card --}}
    <div class="flex justify-center">
        <div class="w-full max-w-sm">

            {{-- Physical Card --}}
            <div style="
                width: 100%;
                aspect-ratio: 85.6 / 54;
                border-radius: 16px;
                background: linear-gradient(135deg, #111 0%, #1a1a1a 40%, #0d0d0d 100%);
                box-shadow: 0 25px 60px rgba(0,0,0,0.7), 0 0 0 1px rgba(255,255,255,0.06), inset 0 1px 0 rgba(255,255,255,0.08);
                position: relative;
                overflow: hidden;
                padding: 22px 24px 20px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            ">
                {{-- Gold top stripe --}}
                <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#e8a020,#f5c842,#e8a020);"></div>

                {{-- Subtle background texture --}}
                <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 80% 0%,rgba(232,160,32,0.12),transparent 55%);pointer-events:none;"></div>
                <div style="position:absolute;bottom:-30px;right:-30px;width:160px;height:160px;border-radius:50%;background:rgba(232,160,32,0.04);pointer-events:none;"></div>
                <div style="position:absolute;bottom:-50px;right:-50px;width:220px;height:220px;border-radius:50%;background:rgba(232,160,32,0.03);pointer-events:none;"></div>

                {{-- Top row: Logo + Badge --}}
                <div style="position:relative;display:flex;align-items:flex-start;justify-content:space-between;">
                    <div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <img src="{{ asset('images/amigos1.png') }}" alt="Amigos" style="height:20px;width:auto;opacity:0.9;">
                        </div>
                        <div style="font-size:9px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:rgba(232,160,32,0.7);margin-top:4px;">Membership Card</div>
                    </div>
                    @if($membership)
                        @php
                            $now = \Illuminate\Support\Carbon::today();
                            $daysLeft = $membership->expires_at ? (int) $now->diffInDays($membership->expires_at, false) : null;
                            $isExpiring = $daysLeft !== null && $daysLeft <= 7 && $daysLeft >= 0;
                            $isExpired = $daysLeft !== null && $daysLeft < 0;
                        @endphp
                        @if($isExpired)
                            <span style="font-size:10px;font-weight:700;letter-spacing:0.05em;color:#fca5a5;background:rgba(248,113,113,0.12);border:1px solid rgba(248,113,113,0.3);padding:3px 10px;border-radius:999px;">EXPIRED</span>
                        @elseif($isExpiring)
                            <span style="font-size:10px;font-weight:700;letter-spacing:0.05em;color:#fcd34d;background:rgba(251,191,36,0.12);border:1px solid rgba(251,191,36,0.35);padding:3px 10px;border-radius:999px;">EXPIRING</span>
                        @else
                            <span style="font-size:10px;font-weight:700;letter-spacing:0.05em;color:#6ee7b7;background:rgba(52,211,153,0.12);border:1px solid rgba(52,211,153,0.3);padding:3px 10px;border-radius:999px;">ACTIVE</span>
                        @endif
                    @else
                        <span style="font-size:10px;font-weight:700;letter-spacing:0.05em;color:#a1a1aa;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);padding:3px 10px;border-radius:999px;">NO PLAN</span>
                    @endif
                </div>

                {{-- Middle: Member Info --}}
                <div style="position:relative;margin-top:auto;">
                    <div style="font-size:20px;font-weight:800;color:#fff;letter-spacing:0.02em;text-transform:uppercase;line-height:1.1;">{{ $user->name }}</div>
                    <div style="font-family:monospace;font-size:11px;color:rgba(255,255,255,0.3);margin-top:3px;">{{ $memberId }}</div>
                </div>

                {{-- Bottom row: Plan + Expiry --}}
                <div style="position:relative;display:flex;justify-content:space-between;align-items:flex-end;margin-top:12px;padding-top:12px;border-top:1px solid rgba(255,255,255,0.07);">
                    @if($membership)
                        <div>
                            <div style="font-size:8px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:rgba(255,255,255,0.3);">Plan</div>
                            <div style="font-size:13px;font-weight:700;color:#e8a020;margin-top:2px;">{{ $membership->plan?->name ?? '—' }}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:8px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:rgba(255,255,255,0.3);">Expires</div>
                            <div style="font-size:13px;font-weight:700;color:#fff;margin-top:2px;">{{ $membership->expires_at?->format('M j, Y') ?? '—' }}</div>
                        </div>
                    @else
                        <div style="font-size:11px;color:rgba(255,255,255,0.3);">No active membership</div>
                    @endif
                </div>
            </div>

            {{-- QR Code Panel --}}
            <div class="mt-6 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl shadow-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-white/10 bg-white/5">
                    <h2 class="text-xs font-bold text-amber-400 uppercase tracking-widest">Verification QR Code</h2>
                </div>
                <div class="p-6 flex flex-col items-center gap-4">
                    <div class="rounded-lg bg-white p-3">
                        {!! $qrSvg !!}
                    </div>
                    <p class="text-xs text-gray-400 text-center">Show this QR code at the front desk to verify your membership status.</p>
                    <a href="{{ route('portal.card.pdf') }}"
                       download="amigos-membership-card.pdf"
                       wire:navigate.void
                       class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-500 hover:to-yellow-600 transition-all text-black font-bold text-sm px-5 py-2.5 rounded-xl shadow-[0_0_15px_rgba(251,191,36,0.2)] hover:shadow-[0_0_20px_rgba(251,191,36,0.4)] transform hover:-translate-y-0.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download PDF Card
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>