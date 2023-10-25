<?php

namespace App\Http;

use App\Entity\Transaction;
use Symfony\Component\Validator\Constraints as Assert;

class MakeTransferRequest
{
    public function __construct(
        #[
            Assert\NotBlank,
            Assert\Type(type: 'integer'),
        ]
        public int $from_balance_id,
        #[
            Assert\NotBlank,
            Assert\Type(type: 'integer'),
        ]
        public int $to_balance_id,
        #[
            Assert\NotBlank,
            Assert\Positive,
            Assert\LessThan(
                value: Transaction::TRANSFER_LIMIT,
                message: 'maximum transfer is ' . Transaction::TRANSFER_LIMIT,
            )
        ]
        public int $amount,
    )
    {
    }
}