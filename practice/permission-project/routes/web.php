<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ─── Auth Routes ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', fn() => redirect()->route('login'));

// ─── Super Admin Routes ────────────────────────────────────────────────────────
Route::prefix('superadmin')->name('superadmin.')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    // User management
    Route::get('/users',                    [SuperAdminController::class, 'users'])->name('users');
    Route::get('/users/create',             [SuperAdminController::class, 'createUser'])->name('users.create');
    Route::post('/users',                   [SuperAdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit',        [SuperAdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}',             [SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}',          [SuperAdminController::class, 'destroyUser'])->name('users.destroy');
    Route::post('/users/{user}/toggle',     [SuperAdminController::class, 'toggleUserStatus'])->name('users.toggle');

    // Access management
    Route::get('/access',                   [SuperAdminController::class, 'accessManagement'])->name('access');
    Route::post('/access/grant',            [SuperAdminController::class, 'grantAccess'])->name('access.grant');
    Route::delete('/access/{grant}/revoke', [SuperAdminController::class, 'revokeAccess'])->name('access.revoke');
    Route::post('/access/revoke-multiple',  [SuperAdminController::class, 'revokeMultipleAccess'])->name('access.revoke-multiple');
    Route::get('/access/all',               [SuperAdminController::class, 'allGrants'])->name('access.all');

    // Products & Orders
    Route::get('/products', [SuperAdminController::class, 'products'])->name('products');
    Route::get('/orders',   [SuperAdminController::class, 'orders'])->name('orders');
});

// ─── Admin Routes ──────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Vendors (read only, granted by super admin)
    Route::get('/vendors',                  [AdminController::class, 'vendors'])->name('vendors');
    Route::get('/vendors/{vendor}',         [AdminController::class, 'viewVendor'])->name('vendors.show');

    // User management
    Route::get('/users',                    [AdminController::class, 'users'])->name('users');
    Route::get('/users/create',             [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users',                   [AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{user}',          [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Access management
    Route::get('/access',                   [AdminController::class, 'accessManagement'])->name('access');
    Route::post('/access/grant',            [AdminController::class, 'grantAccess'])->name('access.grant');
    Route::delete('/access/{grant}/revoke', [AdminController::class, 'revokeAccess'])->name('access.revoke');
    Route::post('/access/revoke-multiple',  [AdminController::class, 'revokeMultipleAccess'])->name('access.revoke-multiple');

    // Products & Orders
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/orders',   [AdminController::class, 'orders'])->name('orders');
});

// ─── Vendor Routes ─────────────────────────────────────────────────────────────
Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');

    // Products
    Route::get('/products',               [VendorController::class, 'products'])->name('products');
    Route::get('/products/create',        [VendorController::class, 'createProduct'])->name('products.create');
    Route::post('/products',              [VendorController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit',[VendorController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}',     [VendorController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}',  [VendorController::class, 'destroyProduct'])->name('products.destroy');

    // Orders
    Route::get('/orders',                                 [VendorController::class, 'orders'])->name('orders');
    Route::post('/orders/{order}/status',                 [VendorController::class, 'updateOrderStatus'])->name('orders.status');

    // Users (granted access)
    Route::get('/users',                  [VendorController::class, 'users'])->name('users');
    Route::get('/users/{user}',           [VendorController::class, 'viewUser'])->name('users.show');

    // Access management
    Route::get('/access',                   [VendorController::class, 'accessManagement'])->name('access');
    Route::post('/access/grant',            [VendorController::class, 'grantAccess'])->name('access.grant');
    Route::delete('/access/{grant}/revoke', [VendorController::class, 'revokeAccess'])->name('access.revoke');
    Route::post('/access/revoke-multiple',  [VendorController::class, 'revokeMultipleAccess'])->name('access.revoke-multiple');
});

// ─── User Routes ───────────────────────────────────────────────────────────────
Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard',  [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/products',   [UserController::class, 'products'])->name('products');
    Route::get('/orders',     [UserController::class, 'orders'])->name('orders');
    Route::post('/order',     [UserController::class, 'placeOrder'])->name('order');
    Route::get('/profile',    [UserController::class, 'profile'])->name('profile');
});
