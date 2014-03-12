<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Notifier\NotifierInterface;
use IDCI\Bundle\NotificationBundle\Exception\UndefinedNotifierException;
use IDCI\Bundle\NotificationBundle\Event\NotificationEvent;
use IDCI\Bundle\NotificationBundle\Event\NotificationEvents;

class NotificationManager extends AbstractManager
{
    protected $notifiers;

    /**
     * Constructor
     *
     * @param ObjectManager $objectManager
     * @param EventDispatcherInterface $entityManager
     */
    public function __construct(ObjectManager $objectManager, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($objectManager, $eventDispatcher);
        $this->notifiers = array();
    }

    /**
     * Get Repository
     *
     * @return \Doctrine\ORM\EntityManager\EntityRepository
     */
    protected function getRepository()
    {
        return $this->getObjectManager()->getRepository("IDCINotificationBundle:Notification");
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
            NotificationEvents::PRE_CREATE,
            new NotificationEvent($entity)
        );

        parent::add($entity);

        $this->getEventDispatcher()->dispatch(
            NotificationEvents::POST_CREATE,
            new NotificationEvent($entity)
        );
    }

    /**
     * Update
     * Use the object manager to update (persist) the given object
     *
     * @param object $entity
     */
    public function update($entity)
    {
        $this->getEventDispatcher()->dispatch(
            NotificationEvents::PRE_UPDATE,
            new NotificationEvent($entity)
        );

        parent::update($entity);

        $this->getEventDispatcher()->dispatch(
            NotificationEvents::POST_UPDATE,
            new NotificationEvent($entity)
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
            NotificationEvents::PRE_DELETE,
            new NotificationEvent($entity)
        );

        parent::delete($entity);

        $this->getEventDispatcher()->dispatch(
            NotificationEvents::POST_DELETE,
            new NotificationEvent($entity)
        );
    }

    /**
     * Get notifiers
     *
     * @return array
     */
    public function getNotifiers()
    {
        return $this->notifiers;
    }

    /**
     * Add notifier
     *
     * @param NotifierInterface $notifier
     * @param string $alias
     */
    public function addNotifier(NotifierInterface $notifier, $alias)
    {
        $this->notifiers[$alias] = $notifier;
    }

    /**
     * Get notifier
     *
     * @param string $alias
     * @return NotifierInterface
     */
    public function getNotifier($alias)
    {
        if (!isset($this->notifiers[$alias])) {
            throw new UndefinedNotifierException($alias);
        }

        return $this->notifiers[$alias];
    }

    /**
     * Add Notification
     *
     * @param string $type
     * @param array $data
     * @param string|null $sourceName
     */
    public function addNotification($type, $data, $sourceName = null)
    {
        $notifier = $this->getNotifier($type);
        $data = $notifier->cleanData($data);

        $notification = new Notification();
        $notification
            ->setType($type)
            ->setSource(null === $sourceName ? $data['source'] : $sourceName)
            ->setFrom(json_encode($data['from']))
            ->setTo(json_encode($data['to']))
            ->setContent(json_encode($data['content']))
        ;

        $this->getObjectManager()->persist($notification);
        $this->getObjectManager()->flush();
    }

    /**
     * Notify
     *
     * @param Notification $notification
     */
    public function notify(Notification $notification)
    {
        $notifier = $this->getNotifier($notification->getType());
        try {
            $notifier->sendNotification($notification);
            $notification->setStatus(Notification::STATUS_DONE);
        } catch (\Exception $e) {
            $notification->setStatus(Notification::STATUS_ERROR);
            $notification->addLog($e->getMessage());
        }
        $this->getObjectManager()->persist($notification);
        $this->getObjectManager()->flush();
    }
}