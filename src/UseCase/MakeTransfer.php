<?php

namespace App\UseCase;

use App\Contract\BalanceRepoInterface;
use App\Entity\Balance;
use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Exception\BalanceNotFound;
use App\Exception\NotEnoughFundsOnBalance;
use App\Exception\TransferLimitExceeded;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Clock\ClockInterface;
use Throwable;

/**
 * Сделать перевод другому пользователю
 */
readonly class MakeTransfer
{
    /**
     * @param ClockInterface $clock
     * @param EntityManagerInterface $em
     * @param BalanceRepoInterface $balanceRepo
     */
    public function __construct(
        private ClockInterface         $clock,
        private EntityManagerInterface $em,
        private BalanceRepoInterface   $balanceRepo,
    )
    {
    }

    /**
     * @param int $fromBalanceId
     * @param int $toBalanceId
     * @param int $amount
     * @return Balance
     * @throws BalanceNotFound
     * @throws NotEnoughFundsOnBalance
     * @throws TransferLimitExceeded
     * @throws Throwable
     */
    public function do(int $fromBalanceId, int $toBalanceId, int $amount): Balance
    {
        if ($amount > Transaction::TRANSFER_LIMIT) {
            throw new TransferLimitExceeded();
        }

        $now = $this->clock->now();

        try {
            $this->em->beginTransaction();

            // здесь под капотом SELECT ... FOR UPDATE
            $fromBalance = $this->balanceRepo->findOrFail($fromBalanceId, true);

            if ($fromBalance->getAmount() < $amount) {
                throw new NotEnoughFundsOnBalance();
            }

            // здесь под капотом SELECT ... FOR UPDATE
            $toBalance = $this->balanceRepo->findOrFail($toBalanceId, true);

            $fromBalance->addTransaction(
                (new Transaction())
                    ->setType(TransactionType::OUTGOING_TRANSFER)
                    ->setAmount(-$amount)
                    ->setMeta("TO_BALANCE:" . $toBalanceId)
                    ->setCreatedAt($now)
            );

            $toBalance->addTransaction(
                (new Transaction())
                    ->setType(TransactionType::INCOMING_TRANSFER)
                    ->setAmount($amount)
                    ->setMeta("FROM_BALANCE:" . $fromBalanceId)
                    ->setCreatedAt($now)
            );

            $this->em->persist($fromBalance);
            $this->em->persist($toBalance);
            $this->em->flush();

            $this->em->commit();
        } catch (Throwable $exc) {
            $this->em->rollback();
            throw $exc;
        }

        return $fromBalance;
    }
}