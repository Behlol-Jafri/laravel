<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'loginForm'])->name('loginForm');
    Route::get('/signup', [AuthController::class, 'signupForm'])->name('signupForm');
    Route::post('/signup', [UserController::class, 'signup'])->name('signup');
    Route::post('/', [UserController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

});

Route::group(['middleware' => ['role:Super Admin|Admin|Vender|User'],'prefix' => '/dashboard'], function () {
    Route::get('/addUser', [UserController::class, 'addUserForm'])->name('addUser');
    Route::post('/addUser', [UserController::class, 'addUser'])->name('addUser.store');
    Route::get('/users', [UserController::class, 'usersData'])->name('users');
    Route::get('/viewUser/{id}', [UserController::class, 'viewUser'])->name('viewUser');
    Route::get('/updateUser/{id}', [UserController::class, 'updateShowUser'])->name('updateShowUser');
    Route::put('/updateUser/{id}', [UserController::class, 'updateUser'])->name('updateUser');
    Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser'])->name('deleteUser');

    Route::get('/userData', [UserController::class, 'userData'])->name('data');
    Route::get('/users-add-permission/{id}', [UserController::class, 'addPermission'])->name('users.add-permission');
    Route::put('/users-give-permission/{id}', [UserController::class, 'givePermission'])->name('users.give-permission');


    Route::resource('/permissions', PermissionController::class);
    Route::resource('/roles', RoleController::class);
    Route::get('/roles-add-permission/{id}', [RoleController::class, 'addPermission'])->name('roles.add-permission');
    Route::put('/roles-give-permission/{id}', [RoleController::class, 'givePermission'])->name('roles.give-permission');
    Route::get('/manage-access', function () {
        return view('dashboards.manageAccess');
    })->name('manage.access');

     Route::resource('posts', PostController::class);

    Route::resource('category', CategoryController::class);
    
    Route::resource('category.subCategory', SubCategoryController::class);

});
