<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
            $fields = $request->all($this->jsonFields);
            $sign = hash('sha256', implode(':', $fields) . config('payments.data.first.merchant_key'));

            if ($sign !== $request->get('sign') || config('payments.data.first.merchant_id') !== $request->get('merchant_id')) {
                abort(422, 'Signature check failed');
            }
        } else if ($request->header('Content-Type') === 'multipart/form-data') {
            $fields = $request->all($this->formFields);
            $sign = hash('md5', implode('.', $fields) . config('payments.data.second.app_key'));

            if ($sign !== $request->header('Authorization') || config('payments.data.second.app_id') !== $request->get('project_id')) {
                abort(422, 'Signature check failed');
            }
        } else {
            abort(422, 'Unsupported content type');
        }

        return $next($request);
    }
}
