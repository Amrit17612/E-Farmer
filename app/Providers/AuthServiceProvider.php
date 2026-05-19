<?php

namespace App\Providers;

use App\Models\Crop;
use App\Models\Order;
use App\Policies\CropPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Crop::class  => CropPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        \Illuminate\Support\Facades\Gate::define('admin-only', function ($user) {
            return $user->isAdmin();
        });
    }
}
