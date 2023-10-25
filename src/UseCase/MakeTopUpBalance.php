<?php

namespace App\UseCase;

use App\Contract\BalanceRepoInterface;
use App\Entity\Balance;
use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Exception\BalanceNotFound;
use App\Exception\TopUpLimitExceeded;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Clock\ClockInterface;
use Throwable;

/**
 * Пополнить баланс
 */
readonly class MakeTopUpBalance
{
    public function __construct(
        private ClockInterface         $clock,
        private EntityManagerInterface $em,
        private BalanceRepoInterface   $balanceRepo,
    )
    {
    }

    /**
     * @param int $balanceId
     * @param int $amount
     * @return Balance
     * @throws BalanceNotFound
     * @throws TopUpLimitExceeded
     * @throws Throwable
     */
    public function do(int $balanceId, int $amount): Balance
    {
        if ($amount > Transaction::TOP_UP_LIMIT) {
            throw new TopUpLimitExceeded();
        }

        try {
            $this->em->beginTransaction();

            // здесь под капотом SELECT ... FOR UPDATE
            $balance = $this->balanceRepo->findOrFail($balanceId, true);

            $balance
                ->addTransaction(
                    (new Transaction())
                        ->setType(TransactionType::TOP_UP)
                        ->setAmount($amount)
                        ->setMeta("ATM#53546")
                        ->setCreatedAt($this->clock->now())
                );

            $this->em->persist($balance);
            $this->em->flush();
            $this->em->commit();

        } catch (Throwable $exc) {
            $this->em->rollback();
            throw $exc;
        }

        return $balance;
    }
}