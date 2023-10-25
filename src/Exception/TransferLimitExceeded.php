<?php

namespace App\Exception;

use App\Entity\Transaction;

class TransferLimitExceeded extends PublicException
{
    public function __construct()
    {
        parent::__construct(message: 'transfer limit(' . Transaction::TRANSFER_LIMIT . ') exceeded');
    }
}