<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Share market prices on landing page
        view()->composer('welcome', function ($view) {
            try {
                $marketPrices = \App\Models\MarketPrice::latest('price_date')->take(8)->get();
            } catch (\Throwable $exception) {
                report($exception);
                $marketPrices = new Collection();
            }

            $view->with('marketPrices', $marketPrices);
        });
    }
}
