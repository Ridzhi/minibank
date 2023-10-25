<?php

namespace App\UseCase;

use App\Contract\BalanceRepoInterface;
use App\Entity\Balance;
use App\Exception\BalanceNotFound;

/**
 * Получить баланс пользователя.
 * По легенде у пользователя можут быть различные балансы/счета: банковская карта, накопительный счет, кредитка и т.д.
 * Поэтому оперируем не id пользователя а id баланса
 */
readonly class GetBalance
{
    /**
     * @param BalanceRepoInterface $balanceRepo
     */
    public function __construct(
        private BalanceRepoInterface $balanceRepo,
    )
    {
    }

    /**
     * @param int $balanceId
     * @return Balance
     * @throws BalanceNotFound
     */
    public function do(int $balanceId): Balance
    {
        return $this->balanceRepo->findOrFail($balanceId);
    }
}