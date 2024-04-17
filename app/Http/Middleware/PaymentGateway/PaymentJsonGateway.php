<?php

namespace App\Http\Middleware\PaymentGateway;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class PaymentJsonGateway implements PaymentGatewayInterface
{
    private array $jsonFields = [
        'merchant_id',
        'payment_id',
        'status',
        'amount',
        'amount_paid',
        'timestamp'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $fields = $request->all($this->jsonFields);
        ksort($fields);
        $sign = hash('sha256', implode(':', $fields) . config('payments.data.first.merchant_key'));

        if ($sign !== $request->get('sign')) {
            abort(422, 'Signature check failed');
        }

        return $next($request);
    }
}
