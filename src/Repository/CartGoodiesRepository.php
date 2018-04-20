<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class CartGoodiesRepository extends EntityRepository
{
    /**
     * Renvoie les 3 produits les plus commandés
     *
     * @return array
     */
    public function findMostPopularGoodies(): array
    {
        return $this->createQueryBuilder('cg')
            ->where('cg.price IS NOT NULL')
            ->groupBy('cg.goodies')
            ->orderBy('SUM(cg.quantity)', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}
