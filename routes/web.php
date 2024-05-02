<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('users', [UserController::class, 'index']);
Route::post('users', [UserController::class, 'store'])->withoutMiddleware(['auth', 'csrf']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::put('users/{id}', [UserController::class, 'update']);
Route::delete('users/{id}', [UserController::class, 'destroy']);



Route::get('pontos', 'App\Http\Controllers\PontoController@index');
Route::post('pontos', 'App\Http\Controllers\PontoController@store');
Route::get('pontos/{id}', 'App\Http\Controllers\PontoController@show');
Route::put('pontos/{id}', 'App\Http\Controllers\PontoController@update');
Route::delete('pontos/{id}', 'App\Http\Controllers\PontoController@destroy');

