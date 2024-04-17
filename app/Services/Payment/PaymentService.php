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
                'payment_id' => $data['payment_id'],
                'status' => $data['status'],
                'amount' => $data['amount'],
                'amount_paid' => $data['amount_paid'],
                'merchant_id' => $data['merchant_id'],
            ];
        } else {
            $paymentData = [
                'payment_id' => $data['invoice'],
                'status' => $data['status'],
                'amount' => $data['amount'],
                'amount_paid' => $data['amount_paid'],
                'merchant_id' => $data['project'],
            ];
        }

        $payment = Payment::query()
            ->where('merchant_id', $paymentData['merchant_id'])
            ->where('payment_id', $paymentData['payment_id'])
            ->first();

        return $payment?->update($paymentData);
    }
}
