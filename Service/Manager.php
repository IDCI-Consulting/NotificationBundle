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

class Manager
{
    protected $notifiers;
    protected $validator;
    protected $entityManager;

    /**
     * Constructor
     */
    public function __construct($validator, $entityManager)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->clearNotifiers();
    }

    /**
     * Get validator
     *
     * @return Symfony\Component\Validator\Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Get Eentity Manager
     *
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
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
            throw new UndefinedNotifierException();
        }

        return $this->notifiers[$notifierServiceName];
    }

    /**
     * create
     *
     * @param string $type
     * @param Notification|array $data
     * @throw UnavailableNotificationDataException
     * @return NotificationInterface
     */
    public function create($type, $data)
    {
        $notificationProxy = null;

        if ($data instanceof Notification) {
            $notificationProxy = NotificationFactory::createProxyFromObject($type, $data);
        } else {
            $notificationProxy = NotificationFactory::createProxyFromArray($type, $data);
        }

        $errorList = $this->getValidator()->validate($notificationProxy);

        if (count($errorList) > 0) {
            throw new UnavailableNotificationDataException(print_r($errorList, true));
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

