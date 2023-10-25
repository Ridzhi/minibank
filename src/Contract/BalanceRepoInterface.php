<?php

namespace App\Contract;

use App\Entity\Balance;
use App\Exception\BalanceNotFound;

interface BalanceRepoInterface
{
    /**
     * @throws BalanceNotFound
     */
    public function findOrFail(int $id, bool $lock = false): Balance;
}