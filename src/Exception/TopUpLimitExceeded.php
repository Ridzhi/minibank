<?php

namespace App\Exception;

use App\Entity\Transaction;

class TopUpLimitExceeded extends PublicException
{
    public function __construct()
    {
        parent::__construct(message: 'top up limit(' . Transaction::TOP_UP_LIMIT . ') exceeded');
    }
}