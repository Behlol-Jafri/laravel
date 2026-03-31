<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\SecondSubCategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\ThirdSubCategoryController;
use App\Http\Controllers\UserAuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('category' , [CategoryController::class, 'index']);
Route::post('category' , [CategoryController::class, 'store']);
Route::get('category/{id}' , [CategoryController::class, 'show']);

Route::get('subCategory' , [SubCategoryController::class, 'index']);
Route::post('subCategory' , [SubCategoryController::class, 'store']);
Route::get('subCategory/{id}' , [SubCategoryController::class, 'show']);

Route::get('secondSubCategory' , [SecondSubCategoryController::class, 'index']);
Route::post('secondSubCategory' , [SecondSubCategoryController::class, 'store']);
Route::get('secondSubCategory/{id}' , [SecondSubCategoryController::class, 'show']);

Route::get('thirdSubCategory' , [ThirdSubCategoryController::class, 'index']);
Route::post('thirdSubCategory' , [ThirdSubCategoryController::class, 'store']);
Route::get('thirdSubCategory/{id}' , [ThirdSubCategoryController::class, 'show']);

Route::post('signup',[UserAuthenticationController::class,'signup']);
Route::post('login',[UserAuthenticationController::class,'login']);

