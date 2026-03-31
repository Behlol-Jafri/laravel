<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PhoneNumberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('users',UserController::class);

Route::resource('companies',CompanyController::class);

Route::resource('phone-numbers',PhoneNumberController::class);
