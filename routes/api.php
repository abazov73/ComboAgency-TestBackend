<?php

use App\Http\Controllers\API\Payment\PaymentStatusController;
use App\Http\Middleware\PaymentGateway\PaymentGatewayInterface;
use Illuminate\Support\Facades\Route;

Route::post(config('payments.callback_url'), PaymentStatusController::class)->middleware(['throttle:payment-gateway', PaymentGatewayInterface::class]);
