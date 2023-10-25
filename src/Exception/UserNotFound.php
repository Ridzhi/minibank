<?php

namespace App\Exception;

class UserNotFound extends PublicException
{
    public function __construct(int $userId)
    {
        parent::__construct("user=" . $userId . " not found");
    }
}