<?php

use App\Http\Controllers\API\Payment\PaymentStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post(config('payments.callback_url'), PaymentStatusController::class);
