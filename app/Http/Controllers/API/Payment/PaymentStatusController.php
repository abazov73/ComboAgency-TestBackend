<?php

namespace App\Http\Controllers\API\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Payment\PaymentStatusRequest;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    public function __invoke(PaymentStatusRequest $request, PaymentServiceInterface $paymentService)
    {
        $updated = $paymentService->update($request->getPaymentData());

        if ($updated) {
            return response('success');
        }

        return response('Error processing data', 422);
    }
}
