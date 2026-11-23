<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('setup', [AuthController::class, 'showSetup'])->name('setup');
    Route::post('setup', [AuthController::class, 'setup']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/', 'expenses');

    Route::prefix('api')->group(function () {
        Route::get('expenses', [ExpenseController::class, 'index']);
        Route::post('expenses', [ExpenseController::class, 'store']);
        Route::put('expenses/{expense}', [ExpenseController::class, 'update']);
        Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy']);
        Route::get('settings', [SettingController::class, 'show']);
        Route::put('settings', [SettingController::class, 'update']);
    });
});
