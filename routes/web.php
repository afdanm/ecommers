<?php
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute untuk login dan registrasi
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/password/forgot', function () {
    return view('auth.forgot-password');
})->name('password.request');
Route::post('/password/email', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/password/reset', function () {
    return view('auth.reset-password');
})->name('password.reset');
Route::post('/password/reset', [NewPasswordController::class, 'store'])->name('password.update');   

// Grup route dengan middleware autentikasi
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Grup untuk admin dengan role admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Rute resource untuk produk
        Route::resource('products', ProductController::class);
    });

    // Grup untuk user dengan role user
    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', function () {
            return view('user.dashboard');
        })->name('user.dashboard');
    });

    // Rute untuk dashboard umum
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');
});

// Menambahkan route untuk show produk, jika ingin mendefinisikan secara eksplisit
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
