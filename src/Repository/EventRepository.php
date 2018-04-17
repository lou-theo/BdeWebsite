<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    /**
     * Liste les events passés
     *
     * @return array
     */
    public function findAllPastEvent(): array
    {
        return $this->createQueryBuilder('e')
            ->where(':now > e.eventDate')
            ->setParameter('now', new \DateTime())
            ->orderBy('e.eventDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Liste les events à venir
     *
     * @return array
     */
    public function findAllFutureEvent(): array
    {
        return $this->createQueryBuilder('e')
            ->where(':now <= e.eventDate')
            ->setParameter('now', new \DateTime())
            ->orderBy('e.eventDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
