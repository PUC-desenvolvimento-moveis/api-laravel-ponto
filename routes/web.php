<?php

use Illuminate\Support\Facades\Route;

Route::get('users', 'UserController@index');
Route::post('users', 'UserController@store');
Route::get('users/{id}', 'UserController@show');
Route::put('users/{id}', 'UserController@update');
Route::delete('users/{id}', 'UserController@destroy');

