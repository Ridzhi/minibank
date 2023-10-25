<?php

namespace App\Exception;

class NotEnoughFundsOnBalance extends PublicException
{
    public function __construct()
    {
        parent::__construct(message: 'not enough funds on balance');
    }
}