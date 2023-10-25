<?php

namespace App\Tests\UseCase;

use App\Contract\BalanceRepoInterface;
use App\Entity\Balance;
use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Exception\TopUpLimitExceeded;
use App\UseCase\MakeTopUpBalance;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\ClockInterface;

class MakeTopUpBalanceTest extends TestCase
{
    private MakeTopUpBalance $useCase;

    private ClockInterface $clock;

    private EntityManagerInterface $em;

    private BalanceRepoInterface $balanceRepo;


    protected function setUp(): void
    {
        $this->clock = $this->createMock(ClockInterface::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->balanceRepo = $this->createMock(BalanceRepoInterface::class);

        $this->useCase = new MakeTopUpBalance($this->clock, $this->em, $this->balanceRepo);
    }

    public function testDoWhenTopUpLimitExceeded(): void
    {
        $this->expectException(TopUpLimitExceeded::class);

        $this->useCase->do(1, Transaction::TOP_UP_LIMIT + 1);
    }

    public function testDo(): void
    {
        $now = new \DateTimeImmutable();
        $balance = new Balance();
        $balanceUpdated = $balance->addTransaction(
            (new Transaction())
                ->setType(TransactionType::TOP_UP)
                ->setAmount(100)
                ->setMeta("ATM#53546")
                ->setCreatedAt($now)
        );

        $this->em
            ->expects($this->once())
            ->method('beginTransaction');

        $this->balanceRepo
            ->expects($this->once())
            ->method('findOrFail')
            ->with(1, true)
            ->willReturn($balance);

        $this->clock
            ->expects($this->once())
            ->method('now')
            ->willReturn($now);

        $this->em
            ->expects($this->once())
            ->method('persist')
            ->with($balanceUpdated);

        $this->em
            ->expects($this->once())
            ->method('flush');

        $this->em
            ->expects($this->once())
            ->method('commit');

        $this->assertEquals(
            $balanceUpdated,
            $this->useCase->do(1, 100),
        );
    }
}
