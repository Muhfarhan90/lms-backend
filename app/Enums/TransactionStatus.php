<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Pending  = 'pending';
    case Paid     = 'paid';
    case Verified = 'verified';
    case Failed   = 'failed';
    case Refunded = 'refunded';
}
