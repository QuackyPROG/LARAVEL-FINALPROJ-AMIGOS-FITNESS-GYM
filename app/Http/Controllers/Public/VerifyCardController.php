<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MemberCardService;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Response;
use Illuminate\View\View;

class VerifyCardController extends Controller
{
    public function __construct(private MemberCardService $cardService) {}

    public function show(string $token): View|Response
    {
        try {
            $payload = $this->cardService->verifyToken($token);
            $user = User::with(['activeMembership.plan'])->find($payload['member_id']);

            if (! $user) {
                abort(404);
            }

            return view('public.verify-card', [
                'valid' => true,
                'user' => $user,
                'membership' => $user->activeMembership,
                'verifiedAt' => now()->format('F j, Y \a\t g:i A'),
            ]);
        } catch (ExpiredException) {
            return view('public.verify-card', [
                'valid' => false,
                'tokenExpired' => true,
                'verifiedAt' => now()->format('F j, Y \a\t g:i A'),
            ]);
        } catch (SignatureInvalidException) {
            return view('public.verify-card', [
                'valid' => false,
                'tokenExpired' => false,
                'verifiedAt' => now()->format('F j, Y \a\t g:i A'),
            ]);
        } catch (\Throwable) {
            abort(404);
        }
    }
}
