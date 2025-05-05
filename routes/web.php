<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductController;
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

// Rute daftar produk & detail produk (semua orang bisa akses)
Route::get('/products', [HomeController::class, 'listProducts'])->name('products.list');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// ==============================
// RUTE AUTHENTIKASI
// ==============================

// Halaman login & register (GET)
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');

// Proses login, register, logout
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Lupa password (GET & POST)
Route::get('/password/forgot', fn() => view('auth.forgot-password'))->name('password.request');
Route::post('/password/email', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/password/reset', fn() => view('auth.reset-password'))->name('password.reset');
Route::post('/password/reset', [NewPasswordController::class, 'store'])->name('password.update');

// ==============================
// RUTE YANG WAJIB LOGIN (AUTH)
// ==============================

Route::middleware('auth')->group(function () {

    // -------------------
    // Profile
    // -------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    

    // -------------------
    // ADMIN (role:admin)
    // -------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::resource('products', ProductController::class);
    });

    // -------------------
    // USER (role:user)
    // -------------------
    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', fn() => view('user.dashboard'))->name('user.dashboard');
    });
});
