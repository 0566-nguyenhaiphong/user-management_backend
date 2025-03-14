<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/user/{id}', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/add', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
