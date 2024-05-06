<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PontoController;

Route::post('login', [UserController::class, 'login']);
Route::post('users', [UserController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::get('auth', [UserController::class, 'auth']);
    });

    Route::prefix('pontos')->group(function () {
        Route::get('/', [PontoController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'getPontos']);
        Route::post('/', [PontoController::class, 'store']);
        Route::get('/{id}', [PontoController::class, 'show']);
        Route::put('/{id}', [PontoController::class, 'update']);
        Route::delete('/{id}', [PontoController::class, 'destroy']);
    });
});
