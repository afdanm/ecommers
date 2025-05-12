<?php

use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

// ==============================
// RUTE UMUM (Tanpa login)
// ==============================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Produk (semua orang bisa lihat)
Route::get('/products', [UserProductController::class, 'index'])->name('products.list');
Route::get('/products/{product}', [UserProductController::class, 'show'])->name('products.show');

// ==============================
// RUTE AUTHENTIKASI
// ==============================

Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/password/forgot', fn() => view('auth.forgot-password'))->name('password.request');
Route::post('/password/email', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/password/reset', fn() => view('auth.reset-password'))->name('password.reset');
Route::post('/password/reset', [NewPasswordController::class, 'store'])->name('password.update');

// ==============================
// RUTE YANG WAJIB LOGIN (AUTH)
// ==============================

Route::middleware('auth')->group(function () {

   

    // ------------------- 
    // ADMIN (role:admin)
    // -------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::resource('products', AdminProductController::class);
        Route::resource('categories', CategoryController::class);
    });

    // ------------------- 
    // USER (role:user)
    // -------------------
    Route::middleware('role:user')->group(function () {

        // -------------------------
        // PROFILE ROUTES
        // -------------------------
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
            Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
        });

        // -------------------------
        // CART ROUTES (Keranjang)
        // -------------------------
        
        


        // -------------------------
        // CHECKOUT ROUTES
        // -------------------------
  
    });
});
