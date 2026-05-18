<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CoachController as AdminCoachController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\LegalDocumentController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\SiteContentController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Portal\CoachesController;
use App\Http\Controllers\Portal\DashboardController as PortalDashboardController;
use App\Http\Controllers\Portal\EventsController;
use App\Http\Controllers\Portal\MemberCardController;
use App\Http\Controllers\Portal\MyMembershipController;
use App\Http\Controllers\Portal\ScheduleController;
use App\Http\Controllers\Portal\SupportController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PaymentResultController;
use App\Http\Controllers\Public\RegisterController;
use App\Http\Controllers\Public\VerifyCardController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::get('/payment/success', [PaymentResultController::class, 'success'])->name('payment.success');
Route::get('/payment/failed', [PaymentResultController::class, 'failed'])->name('payment.failed');
Route::get('/verify/{token}', [VerifyCardController::class, 'show'])->name('verify.card');

// Auth
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Force password change
Route::middleware('auth')->group(function (): void {
    Route::get('/change-password', [ChangePasswordController::class, 'showForm'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password.change.update');
});

// Forgot/reset password
Route::middleware('guest')->group(function (): void {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Member portal
Route::middleware(['auth', 'role:member', 'force.password.change'])
    ->prefix('portal')
    ->name('portal.')
    ->group(function (): void {
        Route::get('/dashboard', [PortalDashboardController::class, 'index'])->name('dashboard');
        Route::get('/card', [MemberCardController::class, 'show'])->name('card');
        Route::get('/card/pdf', [MemberCardController::class, 'downloadPdf'])->name('card.pdf');
        Route::get('/coaches', [CoachesController::class, 'index'])->name('coaches');
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
        Route::get('/events', [EventsController::class, 'index'])->name('events');
        Route::get('/support', [SupportController::class, 'index'])->name('support');
        Route::get('/my-membership', [MyMembershipController::class, 'index'])->name('my-membership');
    });

// Admin panel
Route::middleware(['auth', 'role:admin', 'force.password.change'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/site-content', [SiteContentController::class, 'index'])->name('site-content');
        Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
        Route::get('/members/create', [AdminMemberController::class, 'create'])->name('members.create');
        Route::post('/members', [AdminMemberController::class, 'store'])->name('members.store');
        Route::get('/members/{member}', [AdminMemberController::class, 'show'])->name('members.show');
        Route::get('/members/{member}/gov-id', [AdminMemberController::class, 'govId'])->name('members.gov-id');
        Route::get('/plans', [AdminPlanController::class, 'index'])->name('plans.index');
        Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit-log');
        Route::get('/coaches', [AdminCoachController::class, 'index'])->name('coaches.index');
        Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
        Route::get('/announcements', [AdminCoachController::class, 'announcements'])->name('announcements.index');
        Route::get('/chat', fn () => view('admin.chat'))->name('chat');
        Route::get('/legal', [LegalDocumentController::class, 'index'])->name('legal.index');
        Route::get('/legal/{key}/edit', [LegalDocumentController::class, 'edit'])->name('legal.edit');
    });
