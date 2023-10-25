<?php

namespace App\Repository;

use App\Contract\BalanceRepoInterface;
use App\Entity\Balance;
use App\Exception\BalanceNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Balance>
 *
 * @method Balance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Balance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Balance[]    findAll()
 * @method Balance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BalanceRepository extends ServiceEntityRepository implements BalanceRepoInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Balance::class);
    }

    /**
     * @throws BalanceNotFound
     */
    public function findOrFail(int $id, bool $lock = false): Balance
    {
        // LockMode::PESSIMISTIC_WRITE делает SELECT ... FOR UPDATE
        return $this->find($id, $lock ? LockMode::PESSIMISTIC_WRITE : null) ?? throw new BalanceNotFound($id);
    }
}
