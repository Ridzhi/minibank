<?php

namespace App\Tests\UseCase;

use App\Contract\BalanceRepoInterface;
use App\Entity\Balance;
use App\UseCase\GetBalance;
use PHPUnit\Framework\TestCase;

class GetBalanceTest extends TestCase
{
    public function testDo(): void
    {
        $balance = new Balance();

        $repo = $this->createMock(BalanceRepoInterface::class);

        $repo->expects($this->once())
            ->method('findOrFail')
            ->with(1)
            ->willReturn($balance);

        $this->assertEquals(
            $balance,
            (new GetBalance($repo))->do(1)
        );
    }
}
