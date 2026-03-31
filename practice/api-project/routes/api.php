<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('signup', [UserController::class, 'signup']);

Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [UserController::class, 'logout']);
    Route::apiResource('posts', PostController::class);
});
