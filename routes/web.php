<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PontoController;

Route::post('login', [UserController::class, 'login']);
Route::post('users', [UserController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function () {
Route::get('users', [UserController::class, 'index']);
Route::get('user/{id}', [UserController::class, 'show']);
Route::put('user/{id}', [UserController::class, 'update']);
Route::delete('user/{id}', [UserController::class, 'destroy']);
Route::get('auth', [UserController::class, 'auth']);
});

Route::get('users/pontos/{id}', [UserController::class, 'getPontos']);

Route::get('pontos', [PontoController::class, 'index']);
Route::post('pontos', [PontoController::class, 'store']);
Route::get('pontos/{id}', [PontoController::class, 'show']);
Route::put('pontos/{id}', [PontoController::class, 'update']);
Route::delete('pontos/{id}', [PontoController::class, 'destroy']);




 





