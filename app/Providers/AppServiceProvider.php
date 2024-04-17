<?php

namespace App\Providers;

use App\Http\Middleware\PaymentGateway\PaymentFormGateway;
use App\Http\Middleware\PaymentGateway\PaymentGatewayInterface;
use App\Http\Middleware\PaymentGateway\PaymentJsonGateway;
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

        $this->app->bind(
            PaymentGatewayInterface::class,
            request()->getContentTypeFormat() === 'json'
                ? PaymentJsonGateway::class
                : PaymentFormGateway::class,
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
