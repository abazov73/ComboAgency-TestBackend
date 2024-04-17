<?php

namespace App\Http\Middleware\PaymentGateway;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class PaymentFormGateway
{
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
        if ($request->getContentTypeFormat() !== 'form') {
            abort(422, 'Unsupported format');
        }

        $fields = $request->all($this->formFields);
        ksort($fields);
        $sign = hash('md5', implode('.', $fields) . config('payments.data.second.app_key'));

        if ($sign !== $request->header('Authorization')) {
            abort(422, 'Signature check failed');
        }

        return $next($request);
    }
}
