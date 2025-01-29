<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppEventoController;
use App\Http\Controllers\AppCategoriaController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/request-login', [AuthController::class, 'requestLogin']);
Route::post('/login', [AuthController::class, 'login']);


Route::post('/enviar-evento', [AppEventoController::class, 'store']);
Route::get('/categorias', [AppCategoriaController::class, 'index']);