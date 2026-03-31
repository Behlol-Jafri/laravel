<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::view('login', 'login')->name('login');

Route::view('register', 'register')->name('register');

Route::post('registerForm', [UserController::class, 'register'])->name('registerForm');

Route::post('loginForm', [UserController::class, 'login'])->name('loginForm');

Route::get('dashboard', [UserController::class, 'dashboardPage'])->name('dashboard');

Route::get('dashboard/inner', [UserController::class, 'innerPage'])->name('inner');

Route::get('logout', [UserController::class, 'logout'])->name('logout');
