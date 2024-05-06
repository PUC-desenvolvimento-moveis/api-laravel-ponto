<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PontoController;



Route::prefix('v1')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'store']);
});


Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('users')->group(function () {
        Route::get('/all', [UserController::class, 'index']);
        Route::get('show/{id}', [UserController::class, 'show']);
        Route::put('update/{id}', [UserController::class, 'update']);
        Route::delete('destroy/{id}', [UserController::class, 'destroy']);
        Route::get('/auth', [UserController::class, 'auth']);
    })->middleware(['auth:sanctum']);

    Route::prefix('pontos')->group(function () {
        Route::get('/', [PontoController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'getPontos']);
        Route::post('/', [PontoController::class, 'store']);
        Route::get('/{id}', [PontoController::class, 'show']);
        Route::put('/{id}', [PontoController::class, 'update']);
        Route::delete('/{id}', [PontoController::class, 'destroy']);
    });
});
