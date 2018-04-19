<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;

class GoodiesRepository extends EntityRepository
{
    /**
     * Retourne la liste des goodies appartenant à une categorie
     *
     * @param Category $category
     * @return array
     */
    public function findAllGoodiesByCategory(Category $category): array
    {
        return $this->createQueryBuilder('g')
            ->join('g.categories ', 'c')
            ->where('c.id = :category')
            ->setParameter('category', $category->getId())
            ->getQuery()
            ->getResult();
    }
}
