<?php

namespace App\Providers;

use App\Services\Payment\PaymentService;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            PaymentServiceInterface::class,
            PaymentService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('payment-gateway', function (Request $request) {
            return Limit::perDay($request->header('Content-Type') === 'application/json' ? config('payments.limits.first') : config('payments.limits.second'));
        });
    }
}
