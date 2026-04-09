<?php

use App\Http\Controllers\PayMongoWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| IMPORTANT: The PayMongo webhook route MUST be excluded from CSRF
| verification. This is configured in bootstrap/app.php via:
|   $middleware->validateCsrfTokens(except: ['api/webhook/*'])
|
| PayMongo will POST to this endpoint when payment events occur.
| The PayMongoService verifies the webhook signature before processing.
|
*/

// PayMongo webhook — excluded from CSRF, signature-verified internally
Route::post('/webhook/paymongo', [PayMongoWebhookController::class, 'handle'])
    ->name('webhook.paymongo');

// Sanctum-protected API routes
Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
