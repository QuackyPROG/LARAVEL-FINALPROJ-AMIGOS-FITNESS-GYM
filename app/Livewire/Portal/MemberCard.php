<?php

namespace App\Livewire\Portal;

use App\Services\MemberCardService;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemberCard extends Component
{
    public function render(): View
    {
        $user = auth()->user()->load(['activeMembership.plan']);
        $cardService = app(MemberCardService::class);
        $token = $cardService->generateToken($user);

        $qrSvg = QrCode::format('svg')->size(160)->generate(url('/verify/').$token);

        $memberId = '#'.str_pad((string) $user->id, 6, '0', STR_PAD_LEFT);

        return view('livewire.portal.member-card', [
            'user' => $user,
            'membership' => $user->activeMembership,
            'memberId' => $memberId,
            'qrSvg' => $qrSvg,
        ]);
    }
}
