<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OrganizationController;

Route::controller(AuthController::class)->group(function () {
    // Route::get('signup', 'signup')->name('signup');
    Route::post('register', 'register')->name('register');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAuth')->name('login.submit');

    Route::get('forgot-password', 'forgotPassword')->name('password.email');
    Route::post('forgot-password', 'sendResetLink')->name('password.request');
    Route::get('reset-password/{token}', 'resetPassword')->name('password.reset');
    Route::post('reset-password', 'updatePassword')->name('password.update');
});

Route::controller(OrganizationController::class)->group(function () {
    Route::get('organization-signup/{plan}', 'signup')->name('organization.signup');
    Route::post('organization-store', 'store')->name('organization.store');
    Route::get('organization-login', 'login')->name('tenant.login');
});
