<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// メール認証を促すページの表示
Route::get('verify-email', EmailVerificationPromptController::class)
    ->name('verification.notice');
// メール認証の再送信
Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    // 1分間に6回までアクセス可能
    ->middleware('throttle:6,1')
    ->name('verification.send');

// middleware('guest')は、ログインしていないユーザーのみアクセス可能。
Route::middleware('guest')->group(function () {
    // 登録フォームの表示
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    // 登録処理
    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register.store');
    // ログインフォームの表示
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    // ログイン処理
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    // パスワードリセットフォームの表示
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// middleware('auth')は、ログインしているユーザーのみアクセス可能。
Route::middleware('auth')->group(function () {
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
