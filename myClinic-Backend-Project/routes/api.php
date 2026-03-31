<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());

    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/userCreate', [UserController::class, 'store']);
        Route::get('/user/{uuid}', [UserController::class, 'show']);
        Route::put('/userUpdate/{uuid}', [UserController::class, 'update']);
        Route::delete('/userDelete/{uuid}', [UserController::class, 'destroy']);
    });

    Route::middleware('role:clinic')->group(function () {
        // Route::get('/users', [UserController::class, 'index']);
    });

    Route::middleware('role:pt')->group(function () {
        // Route::get('/users', [UserController::class, 'index']);
    });
});
