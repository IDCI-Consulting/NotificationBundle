<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * AbstractEntityRepository
 */
abstract class AbstractEntityRepository extends EntityRepository
{
    /**
     * Find by query builder
     *
     * @param array $criteria
     * @param array|null $orderBy
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByQueryBuilder(array $criteria, array $orderBy = null)
    {
        $qb = $this->createQueryBuilder('entity');

        if(!is_null($orderBy)) {
            foreach($orderBy as $field => $order) {
                $qb->addOrderBy(sprintf("entity.%s", $field), $order);
            }
        }

        $this->addCriteria($qb, 'entity', $criteria);

        return $qb;
    }

    /**
     * Find by query
     *
     * @param array $criteria
     * @param array|null $orderBy
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByQuery(array $criteria = null, array $orderBy = null)
    {
        return $this->findByQueryBuilder($criteria, $orderBy)->getQuery();
    }

    /**
     * Find all query builder
     *
     * @param array|null $orderBy
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllQueryBuilder(array $orderBy = null)
    {
        return $this->findByQueryBuilder(array(), $orderBy);
    }

    /**
     * Find all query
     *
     * @param array|null $orderBy
     *
     * @return \Doctrine\ORM\Query
     */
    public function findAllQuery(array $orderBy = null)
    {
        return $this->findAllQueryBuilder($orderBy)->getQuery();
    }

    /**
     * addCriteria
     *
     * @param QueryBuilder $qb
     * @param string $sourceEntity
     * @param array $criteria
     */
    public function addCriteria(QueryBuilder & $qb, $sourceEntity, array $criteria)
    {
        foreach ($criteria as $field => $value) {
            if (null === $value || (is_array($value) && empty($value))) {
                continue;
            }

            if (is_array($value)) {
                if ($this->getClassMetadata()->hasField($field)) {
                    self::addWhere($qb, $sourceEntity, $field, $value, 'in');
                } else {
                    self::addJoin($qb, $sourceEntity, $field);
                    self::addCriteria($qb, $field, $value);
                }
            } else {
                self::addWhere($qb, $sourceEntity, $field, $value);
            }
        }
    }

    /**
     * addJoin
     *
     * @param QueryBuilder $qb
     * @param string       $relatedEntity
     * @param string       $sourceEntity
     * @param array        $relatedEntityCriteria
     * @param string       $operation (default 'eq')
     */
    protected static function addJoin(QueryBuilder & $qb, $sourceEntity, $relatedEntity, array $relatedEntityCriteria = array(), $operation = 'eq')
    {
        $qb->join(sprintf('%s.%s', $sourceEntity, $relatedEntity), $relatedEntity);

        foreach ($relatedEntityCriteria as $field => $value) {
            self::addWhere($qb, $relatedEntity, $field, $value, $operation);
        }
    }

    /**
     * addWhere
     *
     * @param QueryBuilder $qb
     * @param string       $relatedEntity
     * @param string       $field
     * @param string       $value
     * @param string       $operation (default 'eq')
     */
    protected static function addWhere(QueryBuilder & $qb, $relatedEntity, $field, $value, $operation = 'eq')
    {
        $qb->andWhere(call_user_func_array(
            array($qb->expr(), $operation),
            array(
                sprintf('%s.%s', $relatedEntity, $field),
                $value
            )
        ));
    }
}
