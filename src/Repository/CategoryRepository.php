<?php

namespace App\Repository;

use App\Entity\Category;
use App\Enum\EnumWeekday;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[]
     */
    public function findCategoriesWithTodayProducts(?bool $stopList = null): array
    {
        $today = EnumWeekday::getToday();

        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.products', 'p')
            ->addSelect('p')
            ->andWhere('p.weekday = :today')
            ->setParameter('today', $today)
            ->addOrderBy('p.stopList', 'ASC');

        if ($stopList !== null) {
            $qb->andWhere('p.stopList = :stopList')
                ->setParameter('stopList', $stopList);
        }

        return $qb->getQuery()->getResult();
    }
}
