<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Event;
use Doctrine\ORM\EntityRepository;

/**
 * Class EventRepository.
 */
class EventRepository extends EntityRepository
{
    /**
     * @return null|\AppBundle\Entity\Event
     */
    public function findNextEvent(): ?Event
    {
        $qb = $this->createQueryBuilder('event');

        return $qb
            ->select('event', 'subjects', 'speakers')
            ->leftJoin('event.subjects', 'subjects')
            ->leftJoin('subjects.speakers', 'speakers')
            ->where(
                $qb->expr()->gte('event.scheduledAt', ':scheduleAt')
            )
            ->setParameter('scheduleAt', new \DateTime())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
