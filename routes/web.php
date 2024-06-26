<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PontoController;


/* caso usuario nao estaja authenticado */
Route::get('/unauthenticated', [UserController::class, 'unauthenticated'])->name('login')->middleware(['cors']);


/* rotas iniciais para registro e authenticacao */
Route::prefix('v1')->middleware(['cors'])->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'store']);
});

/*endpoints com acesso authenticado  */
Route::prefix('api')->middleware(['auth:sanctum','cors'])->group(function () {

    Route::prefix('/users')->middleware(['cors'])->group(function () {
        Route::get('/all', [UserController::class, 'index']);
        Route::get('/show/{id}', [UserController::class, 'show']);
        Route::get('/byemail/{email}', [UserController::class, 'getuserbyemail']);
        Route::put('/update/{id}', [UserController::class, 'update']);
        Route::delete('/destroy/{id}', [UserController::class, 'destroy']);
        Route::get('/auth', [UserController::class, 'auth']);
    });

    Route::prefix('/pontos')->middleware(['cors'])->group(function () {
        Route::get('/all', [PontoController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'get_pontos']);
        Route::post('/inicial', [PontoController::class, 'ponto_inicial']);
        Route::get('/show{id}', [PontoController::class, 'show']);
        Route::patch('/update/{id}', [PontoController::class, 'update']);
        Route::patch('/update_hora_final/{id}', [PontoController::class, 'update_hora_final']);
        Route::put('/final/{id}', [PontoController::class, 'bater_ponto_final']);
        Route::delete('destroy/{id}', [PontoController::class, 'destroy']);
        Route::get('/soma_minutos_trabalhados/{id}', [PontoController::class, 'soma_minutos_trabalhados']);
        Route::get('/soma_minutos_trabalhados_por_periodo/{id}/{data_inicial}/{data_final}', [PontoController::class, 'soma_minutos_trabalhados_por_periodo']);
        Route::get('/soma_minutos_trabalhados_por_data/{id}/{data}', [PontoController::class, 'soma_minutos_trabalhados_por_data']);
        Route::get('/verify_horas_ponto_por_dia/{minutos_trabalhados}', [PontoController::class, 'verify_horas_ponto_por_dia']);
        Route::get('/verify_horas_ponto_por_mes/{minutos_trabalhados}', [PontoController::class, 'verify_horas_ponto_por_mes']);
    });
});
