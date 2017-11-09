<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Subject;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class SubjectRepository.
 */
class SubjectRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function findAllPending(): Paginator
    {
        $qb = $this->createQueryBuilder('subject');

        $query = $qb->select('subject', 'votes', 'speakers')
            ->leftJoin('subject.votes', 'votes')
            ->leftJoin('subject.speakers', 'speakers')
            ->where(
                $qb->expr()->eq('subject.status', ':status')
            )
            ->setParameter('status', Subject::STATUS_PENDING)
            ->setFirstResult(0)
            ->setMaxResults(6)
            ->getQuery();

        return new Paginator($query);
    }
}
