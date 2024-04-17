<?php

namespace App\Http\Requests\API\Payment;

use App\Models\Payment;
use App\Utils\Enums\Payments\PaymentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class PaymentStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->getContentTypeFormat() === 'json') {
            return [
                'merchant_id' => 'required|numeric|exists:' . (new Payment())->getTable() . ',merchant_id',
                'payment_id' => 'required|numeric|exists:' . (new Payment())->getTable() . ',payment_id',
                'status' => ['required', 'string' ,Rule::enum(PaymentStatusEnum::class)],
                'amount' => 'required|numeric',
                'amount_paid' => 'required|numeric',
                'timestamp' => 'required|numeric',
                'sign' => 'required|string',
            ];
        } else {
            return [
                'project' => 'required|numeric|exists:' . (new Payment())->getTable() . ',merchant_id',
                'invoice' => 'required|numeric|exists:' . (new Payment())->getTable() . ',payment_id',
                'status' => ['required', 'string' ,Rule::enum(PaymentStatusEnum::class)],
                'amount' => 'required|numeric',
                'amount_paid' => 'required|numeric',
                'rand' => 'required|string',
            ];
        }
    }
}
