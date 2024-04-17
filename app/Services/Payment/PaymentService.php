<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Arr;

class PaymentService implements PaymentServiceInterface
{
    public function update(array $data): bool
    {
        $payment = Payment::query()
            ->find(Arr::get($data, 'id'));

        return $payment?->update($data);
    }
}
