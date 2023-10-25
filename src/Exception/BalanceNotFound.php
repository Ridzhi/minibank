<?php

namespace App\Exception;

class BalanceNotFound extends PublicException
{
    public function __construct(int $balanceId)
    {
        parent::__construct("balance=" . $balanceId . " not found");
    }
}