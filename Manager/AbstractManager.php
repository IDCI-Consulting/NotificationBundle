<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractManager
{
    protected $objectManager;
    protected $eventDispatcher;

    /**
     * Constructor
     *
     * @param ObjectManager $objectManager
     * @param EventDispatcherInterface $entityManager
     */
    public function __construct(ObjectManager $objectManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->objectManager = $objectManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Get the object manager
     *
     * @return ObjectManager
     */
    protected function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * Get the event dispatcher
     *
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * Magic call
     * Triger to repository methods call
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->getRepository(), $method), $args);
    }

    /**
     * Add
     * Use the object manager to add (persist) the given object
     *
     * @param object $entity
     */
    public function add($entity)
    {
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush();
    }

    /**
     * Update
     * Use the object manager to update (persist) the given object
     *
     * @param object $entity
     */
    public function update($entity)
    {
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush();
    }

    /**
     * Delete
     * Use the object manager to delete (remove) the given object
     *
     * @param object $entity
     */
    public function delete($entity)
    {
        $this->getObjectManager()->remove($entity);
        $this->getObjectManager()->flush();
    }

    /**
     * Get Repository
     *
     * @return \Doctrine\ORM\EntityManager\EntityRepository
     */
    abstract protected function getRepository();
}
