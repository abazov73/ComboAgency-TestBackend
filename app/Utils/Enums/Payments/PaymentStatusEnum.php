<?php

namespace App\Utils\Enums\Payments;

enum PaymentStatusEnum: string
{
    case NEW = 'new';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case EXPIRED = 'expired';
    case REJECTED = 'rejected';
}
