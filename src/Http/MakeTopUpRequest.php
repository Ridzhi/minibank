<?php

namespace App\Http;

use App\Entity\Transaction;
use Symfony\Component\Validator\Constraints as Assert;

class MakeTopUpRequest
{
    public function __construct(
        #[
            Assert\NotBlank,
            Assert\Type(type: 'integer'),
        ]
        public int $balance_id,
        #[
            Assert\NotBlank,
            Assert\Positive,
            Assert\LessThan(
                value: Transaction::TOP_UP_LIMIT,
                message: 'maximum top up amount is ' . Transaction::TOP_UP_LIMIT,
            )
        ]
        public int $amount,
    )
    {
    }
}