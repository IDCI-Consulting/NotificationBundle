<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Service;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Factory\NotificationFactory;
use IDCI\Bundle\NotificationBundle\Notifier\NotifierInterface;
use IDCI\Bundle\NotificationBundle\Exception\UnavailableNotificationDataException;
use IDCI\Bundle\NotificationBundle\Util\Inflector;
use IDCI\Bundle\NotificationBundle\Event\NotificationEvent;
use IDCI\Bundle\NotificationBundle\Event\NotificationEvents;

class Manager
{
    protected $notifiers;
    protected $validator;
    protected $entityManager;
    protected $eventDispatcher;

    /**
     * Constructor
     *
     * @param Symfony\Component\Validator\Validator $validator
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(\Symfony\Component\Validator\Validator $validator, \Doctrine\ORM\EntityManager $entityManager, $eventDispatcher)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->clearNotifiers();
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
    public function getRepository()
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * Clear notifiers
     */
    public function clearNotifiers()
    {
        $this->notifiers = array();
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
     */
    public function addNotifier(NotifierInterface $notifier)
    {
        $rc = new \ReflectionClass($notifier);
        $serviceName = Inflector::underscore($rc->getShortName());
        $this->notifiers[$serviceName] = $notifier;
    }

    /**
     * Get notifier
     *
     * @param string $notifierServiceName
     * @return NotifierInterface
     */
    public function getNotifier($notifierServiceName)
    {
        if (!isset($this->notifiers[$notifierServiceName])) {
            throw new UndefinedNotifierException($notifierServiceName);
        }

        return $this->notifiers[$notifierServiceName];
    }

    /**
     * create
     *
     * @param string $type
     * @param Notification|array $data
     * @param string|null $sourceName
     * @throw UnavailableNotificationDataException
     * @return NotificationInterface
     */
    public function create($type, $data, $sourceName = null)
    {
        $notificationProxy = null;

        // Add the source name to the notification parameters if setted
        if($sourceName) {
            $data = array_merge($data, array('source' => $sourceName));
        }

        if ($data instanceof Notification) {
            $notificationProxy = NotificationFactory::createProxyFromObject($type, $data);
        } else {
            $notificationProxy = NotificationFactory::createProxyFromArray($type, $data);
        }

        $errorList = $this->getValidator()->validate($notificationProxy);

        if (count($errorList) > 0) {
            throw new UnavailableNotificationDataException($errorList);
        }

        return $notificationProxy;
    }

    /**
     * Process notifications
     * Associate notifications with the right notifiers
     */
    public function processNotifications()
    {
        $notifications = $this
            ->getEntityManager()
            ->getRepository('IDCINotificationBundle:Notification')
            ->getNotificationsByStatus(Notification::STATUS_NEW)
        ;

        foreach($notifications as $notification) {
            $type = Inflector::underscore(str_replace('Notification', '', $notification->getType()));
            $notificationProxy = $this->create($type, $notification);

            $notifier = $this->getNotifier($notificationProxy->getNotifierServiceName());
            $notifier->addProxyNotification($notificationProxy);
        }
    }

    /**
     * Process notifiers
     * Send notifications associated with notifiers
     *
     * @return array log informations
     */
    public function processNotifiers()
    {
        $logs = array();

        foreach($this->getNotifiers() as $name => $notifier) {
            $notifier->process();
            $logs[$name] = $notifier->getProcessLog();
        }

        return $logs;
    }

    /**
     * Send
     *
     * @return array log informations
     */
    public function send()
    {
        $this->processNotifications();

        return $this->processNotifiers();
    }
}

