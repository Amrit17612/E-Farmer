<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share market prices on landing page
        view()->composer('welcome', function ($view) {
            $marketPrices = \App\Models\MarketPrice::latest('price_date')->take(8)->get();
            $view->with('marketPrices', $marketPrices);
        });
    }
}
