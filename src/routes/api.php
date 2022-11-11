<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Axenso\Sso\Http\Controllers\SsoController;



Route::prefix('api/sso')->group(function () {
    Route::post('login', [SsoController::class, 'login'])->name('sso.login');
    Route::post('register', [SsoController::class, 'register'])->name('sso.register');
    Route::post('verify-email', [SsoController::class, 'verifyEmail'])->name('sso.verify.email');
    Route::post('consent', [SsoController::class, 'consent'])->name('sso.consent');
    Route::post('change-password', [SsoController::class, 'changePassword'])->name('sso.change.password');
    Route::post('request-password-reset', [SsoController::class, 'requestResetPassword'])->name('sso.request.password.reset');
    Route::post('password-reset', [SsoController::class, 'passwordReset'])->name('sso.password.reset');
    Route::post('update-profile', [SsoController::class, 'updateProfile'])->name('sso.password.update.profile');

    
});
