<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\MemberCardService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemberCardController extends Controller
{
    public function __construct(private MemberCardService $cardService) {}

    public function show(): View
    {
        return view('portal.card');
    }

    public function downloadPdf(): Response
    {
        $user = auth()->user()->load(['activeMembership.plan', 'profile']);
        $token = $this->cardService->generateToken($user);
        $qrSvg = QrCode::format('svg')->size(200)->generate(url('/verify/').$token);

        $memberId = '#'.str_pad((string) $user->id, 6, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('portal.card-pdf', compact('user', 'qrSvg', 'memberId'));

        return $pdf->download('membership-card.pdf');
    }
}
