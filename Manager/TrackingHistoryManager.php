<?php

/**
 *
 * @author:  Rémy MENCE <remymence@gmail.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use IDCI\Bundle\NotificationBundle\Entity\TrackingHistory;
use IDCI\Bundle\NotificationBundle\Notifier\NotifierInterface;
use IDCI\Bundle\NotificationBundle\Event\TrackingHistoryEvent;
use IDCI\Bundle\NotificationBundle\Event\TrackingHistoryEvents;

class TrackingHistoryManager extends AbstractManager
{

    /**
     * Constructor
     *
     * @param ObjectManager $objectManager
     * @param EventDispatcherInterface $entityManager
     */
    public function __construct(ObjectManager $objectManager, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($objectManager, $eventDispatcher);
    }

    /**
     * Get Repository
     *
     * @return \Doctrine\ORM\EntityManager\EntityRepository
     */
    protected function getRepository()
    {
        return $this->getObjectManager()->getRepository("IDCINotificationBundle:TrackingHistory");
    }

    /**
     * Add
     * Use the object manager to add (persist) the given object
     *
     * @param object $entity
     */
    public function add($entity)
    {
        $this->getEventDispatcher()->dispatch(
            TrackingHistoryEvents::PRE_CREATE,
            new TrackingHistoryEvent($entity)
        );

        parent::add($entity);

        $this->getEventDispatcher()->dispatch(
            TrackingHistoryEvents::POST_CREATE,
            new TrackingHistoryEvent($entity)
        );
    }

    /**
     * Delete
     * Use the object manager to delete (remove) the given object
     *
     * @param object $entity
     */
    public function delete($entity)
    {
        $this->getEventDispatcher()->dispatch(
            TrackingHistoryEvents::PRE_DELETE,
            new TrackingHistoryEvent($entity)
        );

        parent::delete($entity);

        $this->getEventDispatcher()->dispatch(
            TrackingHistoryEvents::POST_DELETE,
            new TrackingHistoryEvent($entity)
        );
    }
}
