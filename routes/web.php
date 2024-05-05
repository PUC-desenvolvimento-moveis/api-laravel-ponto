<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PontoController;

Route::get('users', [UserController::class, 'index']);
Route::post('users', [UserController::class, 'store']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);

Route::get('users/pontos/{id}', [UserController::class, 'getPontos']);

Route::get('pontos', [PontoController::class, 'index']);
Route::post('pontos', [PontoController::class, 'store']);
Route::get('pontos/{id}', [PontoController::class, 'show']);
Route::put('pontos/{id}', [PontoController::class, 'update']);
Route::delete('pontos/{id}', [PontoController::class, 'destroy']);


 





