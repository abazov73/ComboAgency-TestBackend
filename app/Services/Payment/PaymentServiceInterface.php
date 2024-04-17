<?php

namespace App\Services\Payment;

use App\Models\Payment;

interface PaymentServiceInterface
{
    public function update(array $data): bool;
}
