<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * NotificationRepository
 */
class NotificationRepository extends AbstractEntityRepository
{
    /**
     * Get the number of medias for each mime type
     *
     * @param DateTime from
     * @param DateTime to
     * @param String type
     * @return array
     */
    public function findNumberByStatusForType(\DateTime $from, \DateTime $to, $type)
    {
        return $this
            ->getEntityManager()
            ->createQuery(
                'SELECT n.status, count(n.id) as number
                    FROM IDCINotificationBundle:Notification n
                    WHERE n.createdAt >= :from
                    AND n.createdAt <= :to
                    AND n.type = :type
                    GROUP BY n.status
                '
            )
            ->setParameter('from', $from)
            ->setParameter('to', $to->modify('+1 day'))
            ->setParameter('type', $type)
            ->getResult()
        ;
    }

    /**
     * Get the number of medias for each mime type
     *
     * @return array
     */
    public function findAllTypes()
    {
        return $this
            ->getEntityManager()
            ->createQuery('SELECT DISTINCT n.type FROM IDCINotificationBundle:Notification n')
            ->getResult()
        ;
    }
}
