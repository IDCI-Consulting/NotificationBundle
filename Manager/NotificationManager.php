<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Manager;

use Symfony\Component\Validator\Validator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Factory\NotificationFactory;
use IDCI\Bundle\NotificationBundle\Notifier\NotifierInterface;
use IDCI\Bundle\NotificationBundle\Exception\UnavailableNotificationDataException;
use IDCI\Bundle\NotificationBundle\Util\Inflector;
use IDCI\Bundle\NotificationBundle\Event\NotificationEvent;
use IDCI\Bundle\NotificationBundle\Event\NotificationEvents;

class NotificationManager
{
    protected $notifiers;
    protected $validator;
    protected $entityManager;
    protected $eventDispatcher;

    /**
     * Constructor
     *
     * @param Validator $validator
     * @param EntityManager $entityManager
     * @param EventDispatcher $entityManager
     */
    public function __construct(Validator $validator, EntityManager $entityManager, EventDispatcher $eventDispatcher)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->notifiers = array();
    }

    /**
     * Get validator
     *
     * @return Symfony\Component\Validator\Validator
     */
    protected function getValidator()
    {
        return $this->validator;
    }

    /**
     * Get Entity Manager
     *
     * @return Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Get EventDispatcher
     *
     * @return ContainerAwareEventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * Get Repository
     *
     * @return \Doctrine\ORM\EntityManager\EntityRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository("IDCINotificationBundle:Notification");
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
     * Use the entity manager to add (persist) the given object
     *
     * @param object $entity
     */
    public function add($entity)
    {
        $this->getEventDispatcher()->dispatch(
            NotificationEvents::PRE_CREATE,
            new NotificationEvent($entity)
        );

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            NotificationEvents::POST_CREATE,
            new NotificationEvent($entity)
        );
    }

    /**
     * Update
     * Use the entity manager to update (persist) the given object
     *
     * @param object $entity
     */
    public function update($entity)
    {
        $this->getEventDispatcher()->dispatch(
            NotificationEvents::PRE_UPDATE,
            new NotificationEvent($entity)
        );

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        $this->getEventDispatcher()->dispatch(
            NotificationEvents::POST_UPDATE,
            new NotificationEvent($entity)
        );
    }

    /**
     * Delete
     * Use the entity manager to delete (remove) the given object
     *
     * @param object $entity
     */
    public function delete($entity)
    {
        $this->getEventDispatcher()->dispatch(
            NotificationEvents::PRE_DELETE,
            new NotificationEvent($entity)
        );

        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();

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
        //$this->checkData($data, $notifier->dataValidationMap());

        $notification = new Notification();
        $notification
            ->setType($type)
            ->setSource(null === $sourceName ? $data['source'] : $sourceName)
            ->setTo(json_encode($data['to']))
            ->setContent(json_encode($data['content']))
        ;

        $this->getEntityManager()->persist($notification);
        $this->getEntityManager()->flush();
    }

    /**
     * Check data
     *
     * @param array $data
     * @param array $validationMap
     */
    public function checkData($data, $validationMap)
    {
        $errors = array();
        foreach ($validationMap as $k => $constraint) {
            if (is_array($constraint)) {
                $errors[] = $this->checkData($data[$k], $constraint);
            } else {
                $errors[] = $this->getValidator()->validateValue($data[$k], $constraint);
            }
        }

        if (count($errors) > 0) {
            throw new UnavailableNotificationDataException($errors);
        }

        return true;
    }

    /**
     * Notify
     *
     * @param Notification $notification
     */
    public function notify(Notification & $notification)
    {
        $notifier = $this->getNotifier($notification->getType());
        try {
            $notifier->sendNotification($notification);
            $notification->setStatus(Notification::STATUS_DONE);
        } catch (\Exception $e) {
            $notification->setStatus(Notification::STATUS_ERROR);
            $notification->setLog($e->getMessage());
        }

        $this->getEntityManager()->persist($notification);
        $this->getEntityManager()->flush();
    }
}
