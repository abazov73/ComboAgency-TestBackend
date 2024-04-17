<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class PaymentGatewayCheck
{
    private array $jsonFields = [
        'merchant_id',
        'payment_id',
        'status',
        'amount',
        'amount_paid',
        'timestamp'
    ];

    private array $formFields = [
        'project',
        'invoice',
        'status',
        'amount',
        'amount_paid',
        'rand',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('Content-Type') === 'application/json') {
            if (RateLimiter::attempts('json-gateway') + 1 > config('payments.limits.first')) {
                abort(422, 'Too many attempts');
            }

            $fields = $request->all($this->jsonFields);
            $sign = hash('sha256', implode(':', $fields) . config('payments.data.first.merchant_key'));

            if ($sign !== $request->get('sign') || config('payments.data.first.merchant_id') !== $request->get('merchant_id')) {
                abort(422, 'Signature check failed');
            }

            RateLimiter::increment('json-gateway', 86400);
        } else if (str_starts_with($request->header('Content-Type'), 'multipart/form-data')) {
            if (RateLimiter::attempts('form-gateway') + 1 > config('payments.limits.second')) {
                abort(422, 'Too many attempts');
            }

            $fields = $request->all($this->formFields);
            $sign = hash('md5', implode('.', $fields) . config('payments.data.second.app_key'));

            if ($sign !== $request->header('Authorization') || config('payments.data.second.app_id') != $request->get('project')) {
                abort(422, 'Signature check failed');
            }

            RateLimiter::increment('form-gateway', 86400);
        } else {
            abort(422, 'Unsupported content type');
        }

        return $next($request);
    }
}
