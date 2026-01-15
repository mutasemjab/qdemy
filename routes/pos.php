<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POS\AuthController;
use App\Http\Controllers\POS\CardController;

/*
|--------------------------------------------------------------------------
| POS Routes
|--------------------------------------------------------------------------
|
| Routes for Point of Sale (POS) authentication and card management
|
*/

Route::middleware('guest:pos')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('pos.login');
    Route::post('/login', [AuthController::class, 'login'])->name('pos.login.store');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('pos.forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'sendResetEmail'])->name('pos.send-reset-email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('pos.reset-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('pos.reset-password.store');
});

Route::middleware('auth:pos')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('pos.logout');

    // Dashboard
    Route::get('/dashboard', [CardController::class, 'index'])->name('pos.dashboard');

    // Cards
    Route::get('/cards', [CardController::class, 'index'])->name('pos.cards.index');
    Route::get('/cards/{card}', [CardController::class, 'show'])->name('pos.cards.show');
    Route::get('/card-numbers/{cardNumber}/print', [CardController::class, 'printNumber'])->name('pos.cards.print-number');
    Route::post('/card-numbers/{cardNumber}/confirm-print', [CardController::class, 'confirmPrint'])->name('pos.cards.confirm-print');

    // Profile Settings
    Route::get('/settings/edit', [AuthController::class, 'showEdit'])->name('pos.settings.edit');
    Route::post('/settings/update', [AuthController::class, 'updateProfile'])->name('pos.profile.update');
});
