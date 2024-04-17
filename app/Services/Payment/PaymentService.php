<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Arr;

class PaymentService implements PaymentServiceInterface
{
    public function update(array $data): bool
    {
        if (request()->getContentTypeFormat() === 'json') {
            $paymentData = [
                'id' => Arr::get($data, 'payment_id'),
                'status' => Arr::get($data, 'status'),
                'amount' => Arr::get($data, 'amount'),
                'amount_paid' => Arr::get($data, 'amount_paid'),
                'merchant_id' => Arr::get($data, 'merchant_id'),
            ];
        } else if (request()->getContentTypeFormat() === 'form') {
            $paymentData = [
                'id' => Arr::get($data, 'invoice'),
                'status' => Arr::get($data, 'status'),
                'amount' => Arr::get($data, 'amount'),
                'amount_paid' => Arr::get($data, 'amount_paid'),
                'merchant_id' => Arr::get($data, 'project'),
            ];
        } else {
            return false;
        }

        $payment = Payment::query()
            ->find(Arr::get($paymentData, 'id'));

        return $payment?->update($paymentData);
    }
}
