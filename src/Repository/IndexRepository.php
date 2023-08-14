<?php

namespace App\Repository;

use App\Entity\Index;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Index>
 *
 * @method Index|null find($id, $lockMode = null, $lockVersion = null)
 * @method Index|null findOneBy(array $criteria, array $orderBy = null)
 * @method Index[]    findAll()
 * @method Index[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Index::class);
    }

    /**
     * @throws QueryException
     */
    public function getHistory(int $searchForDays = 7, bool $toIterable = false)
    {
        $query = $this->createQueryBuilder('i')
            ->addCriteria(self::createCreatedAtCriteria($searchForDays))
            ->orderBy('i.tag, i.created_at')
            ->getQuery();

        return $toIterable ? $query->toIterable() : $query->getResult();
    }

    /**
     * @param int $forDays
     * @return Criteria
     */
    public static function createCreatedAtCriteria(int $searchForDays): Criteria
    {
        $date = new \DateTimeImmutable();
        $criteria = Criteria::expr()->gte('created_at', $date->sub(new \DateInterval("P{$searchForDays}D")));

        return Criteria::create()->andWhere($criteria);
    }
}
