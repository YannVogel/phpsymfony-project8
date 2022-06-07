<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Task;
use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    /**
     * @param int $userId
     * @return Task
     */
    public function findReadOnlyTask(int $userId): Task
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where('t.user != :identifier')
            ->andWhere('t.user IS NOT NULL')
           ->setParameter('identifier', $userId);

        return $qb->getQuery()
                  ->getResult()[0];
    }
}
