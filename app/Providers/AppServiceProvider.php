<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $cart = Session::get('cart', []);
            $cartCount = array_sum(array_column($cart, 'quantity'));
            $view->with('cartCount', $cartCount);
        });
    }
    public const HOME = '/redirect-by-role';

}
