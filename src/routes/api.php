<?php

use Axenso\Sso\Http\Controllers\SsoController;
use Illuminate\Support\Facades\Route;



Route::prefix('api/sso')->group(function () {
    Route::post('login', [SsoController::class, 'login'])->name('sso.login');
    Route::post('register', [SsoController::class, 'register'])->name('sso.register');
    Route::post('verify-email', [SsoController::class, 'verifyEmail'])->name('sso.verify.email');
});
