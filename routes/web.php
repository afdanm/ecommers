<?php

use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\TransactionController; // Added missing controller
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTE UMUM (Tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Produk (semua orang bisa lihat)
Route::get('/products', [UserProductController::class, 'index'])->name('products.list');
Route::get('/products/{product}', [UserProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| RUTE AUTHENTIKASI
|--------------------------------------------------------------------------
*/

Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/password/forgot', fn() => view('auth.forgot-password'))->name('password.request');
Route::post('/password/email', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/password/reset', fn() => view('auth.reset-password'))->name('password.reset');
Route::post('/password/reset', [NewPasswordController::class, 'store'])->name('password.update');

/*
|--------------------------------------------------------------------------
| RUTE YANG WAJIB LOGIN (AUTH)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |----------------------------------------------------------------------
    | ADMIN ROUTES (role: admin)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::resource('products', AdminProductController::class);
        Route::resource('categories', CategoryController::class);
    });

    /*
    |----------------------------------------------------------------------
    | USER ROUTES (role: user)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:user')->group(function () {

        // Profile Routes
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [ProfileController::class, 'update'])->name('update');
        });

        // Cart Routes (Keranjang)
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::post('/{product}/add', [CartController::class, 'addToCart'])->name('add');
            Route::get('/', [CartController::class, 'index'])->name('index');
            // Changed from Route::post to Route::delete to support DELETE method
            Route::delete('/remove/{cart}', [CartController::class, 'remove'])->name('remove');
        });

        // Checkout Routes
        Route::prefix('checkout')->name('checkout.')->group(function () {
            Route::get('/', [CheckoutController::class, 'index'])->name('index');
            Route::post('/', [CheckoutController::class, 'process'])->name('process');

           // Ini cukup success dan error aja
            Route::get('/success', [CheckoutController::class, 'success'])->name('success');
            Route::get('/error', [CheckoutController::class, 'error'])->name('error');
            
        });

        //transaction history
        Route::prefix('transaction-history')->name('transaction-history.')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
        });

    });
});