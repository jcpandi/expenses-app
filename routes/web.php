<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'expenses');

Route::prefix('api')->group(function () {
    Route::get('expenses', [ExpenseController::class, 'index']);
    Route::post('expenses', [ExpenseController::class, 'store']);
    Route::put('expenses/{expense}', [ExpenseController::class, 'update']);
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy']);
    Route::get('settings', [SettingController::class, 'show']);
    Route::put('settings', [SettingController::class, 'update']);
});
