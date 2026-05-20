<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\MemberCardService;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;

class MemberCardController extends Controller
{
    public function __construct(private MemberCardService $cardService) {}

    public function show(): View
    {
        return view('portal.card');
    }

    public function downloadPdf(): \Illuminate\Http\Response
    {
        $user  = auth()->user()->load(['activeMembership.plan']);
        $token = $this->cardService->generateToken($user);

        // ── Logo: resize to 60×60 to keep PDF small ────────────────────────
        $logoBase64 = null;
        $logoPath   = public_path('images/amigos1.png');
        if (file_exists($logoPath) && function_exists('imagecreatefrompng')) {
            $src   = imagecreatefrompng($logoPath);
            $thumb = imagecreatetruecolor(60, 60);
            // Preserve transparency
            imagesavealpha($thumb, true);
            imagefill($thumb, 0, 0, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
            imagecopyresampled($thumb, $src, 0, 0, 0, 0, 60, 60, imagesx($src), imagesy($src));
            ob_start();
            imagepng($thumb, null, 9);
            $logoBase64 = 'data:image/png;base64,' . base64_encode(ob_get_clean());
            imagedestroy($src);
            imagedestroy($thumb);
        }

        // ── QR code as HTML table of colored cells ──────────────────────────
        // DomPDF: 1 CSS px = 1 PDF pt = 1/72 inch.
        // 2px × ~29 modules = ~58pt = 20.5mm. Fits cleanly in the right col.
        $url    = url('/verify/') . $token;
        $qrCode = Encoder::encode($url, ErrorCorrectionLevel::M());
        $matrix = $qrCode->getMatrix();
        $size   = $matrix->getWidth();
        $cellPx = 2;

        $rows = '';
        for ($y = 0; $y < $size; $y++) {
            $cells = '';
            for ($x = 0; $x < $size; $x++) {
                $bg     = $matrix->get($x, $y) ? '#000000' : '#ffffff';
                $cells .= '<td style="width:' . $cellPx . 'px;height:' . $cellPx . 'px;'
                         . 'background:' . $bg . ';padding:0;border:none;font-size:0;line-height:0;"></td>';
            }
            $rows .= '<tr>' . $cells . '</tr>';
        }

        $qrHtml = '<table cellspacing="0" cellpadding="0" '
                . 'style="border-collapse:collapse;background:#ffffff;">'
                . $rows
                . '</table>';

        $memberId = '#' . str_pad((string) $user->id, 6, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('portal.card-pdf', compact('user', 'qrHtml', 'memberId', 'logoBase64'))
            ->setPaper([0, 0, 241.89, 153.07], 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'defaultFont'          => 'arial',
                'dpi'                  => 150,
            ]);

        $filename = 'amigos-membership-card-' . str_pad((string) $user->id, 6, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->download($filename);
    }
}
