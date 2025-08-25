<?php

namespace App\Repository;

use App\Entity\Product;
use App\Enum\EnumWeekday;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of Product objects available today
     */
    public function findTodayProducts(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('p');

        $this->applyFilters($qb, $filters);

        return $qb->getQuery()->getResult();
    }

    private function applyFilters($qb, array $filters): void
    {
        if (!empty($filters['name'])) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $filters['name'] . '%');
        }

        if (isset($filters['stopList'])) {
            $stopList = filter_var($filters['stopList'], FILTER_VALIDATE_BOOLEAN);
            $qb->andWhere('p.stopList = :stopList')
                ->setParameter('stopList', $stopList);
        }

        if (!empty($filters['price'])) {
            $this->applyPriceFilter($qb, $filters['price']);
        }

        if (!empty($filters['weekday'])) {
            $this->applyWeekdayFilter($qb, $filters['weekday']);
        } else {
            $today = EnumWeekday::getToday();
            $qb->andWhere('p.weekday = :today')
                ->setParameter('today', $today);
        }

        if (!empty($filters['category'])) {
            $this->applyCategoryFilter($qb, $filters['category']);
        }
    }

    private function applyPriceFilter($qb, $priceFilter): void
    {
        if (is_array($priceFilter)) {
            if (isset($priceFilter['gte'])) {
                $qb->andWhere('p.price >= :priceGte')
                    ->setParameter('priceGte', $priceFilter['gte']);
            }
            if (isset($priceFilter['lte'])) {
                $qb->andWhere('p.price <= :priceLte')
                    ->setParameter('priceLte', $priceFilter['lte']);
            }
            if (isset($priceFilter['between'])) {
                $range = explode('..', $priceFilter['between']);
                if (count($range) === 2) {
                    $qb->andWhere('p.price BETWEEN :priceMin AND :priceMax')
                        ->setParameter('priceMin', $range[0])
                        ->setParameter('priceMax', $range[1]);
                }
            }
        } else {
            // Точное значение
            $qb->andWhere('p.price = :price')
                ->setParameter('price', $priceFilter);
        }
    }

    private function applyWeekdayFilter($qb, $weekday): void
    {
        if (is_array($weekday)) {
            if (!empty($weekday)) {
                $qb->andWhere('p.weekday IN (:weekdays)')
                    ->setParameter('weekdays', $weekday);
            }
        } else {
            $qb->andWhere('p.weekday = :weekday')
                ->setParameter('weekday', $weekday);
        }
    }

    private function applyCategoryFilter($qb, $category): void
    {
        if (is_array($category)) {
            if (!empty($category)) {
                $qb->andWhere('p.category IN (:categories)')
                    ->setParameter('categories', $category);
            }
        } else {
            $qb->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }
    }
}
