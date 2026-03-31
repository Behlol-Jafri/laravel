<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserDataController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// Dashboard Route ✅ yeh add karo
Route::get('/dashboard', function () {
    if (Auth::user()->role ==='admin') {
        return redirect()->route('admin.permissions');
    }
    return redirect()->route('users.index');
})->middleware('auth')->name('dashboard');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
    Route::post('/permissions/toggle', [PermissionController::class, 'toggle'])->name('permissions.toggle');
});

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserDataController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserDataController::class, 'show'])->name('users.show');
});

