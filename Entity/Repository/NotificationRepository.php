<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * NotificationRepository 
 */
class NotificationRepository extends EntityRepository
{
    /**
     * Find by query builder
     * 
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByQueryBuilder(array $criteria = null, array $orderBy = null)
    {
        $qb = $this->createQueryBuilder('entity');

        if(!is_null($orderBy)) {
            foreach($orderBy as $field => $order) {
                $qb->addOrderBy(sprintf("entity.%s", $field), $order);
            }
        }
        
        if(!is_null($criteria)) {
            foreach($criteria as $name => $value) {
                $qb->andWhere(sprintf('entity.%s = %s', $name, $value));
            }
        }

        return $qb;
    }

    /**
     * Find by query
     *
     * @param array $criteria
     * @return \Doctrine\ORM\Query
     */
    public function findByQuery($criteria = null)
    {
        return $this->findByQueryBuilder($criteria)->getQuery();
    }

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
