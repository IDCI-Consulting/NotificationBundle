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
     * getValidator
     *
     * @return Symfony\Component\Validator\Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * getEntityManager
     *
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * clearNotifiers
     *
     * @return array
     */
    public function clearNotifiers()
    {
        $this->notifiers = array();
    }

    /**
     * getNotifiers
     *
     * @return array
     */
    public function getNotifiers()
    {
        return $this->notifiers;
    }

    /**
     * addNotifiers
     *
     * @params NotifierInterface $notifier
     */
    public function addNotifier(NotifierInterface $notifier)
    {
        $rc = new \ReflectionClass($notifier);
        $serviceName = Inflector::underscore($rc->getShortName());
        $this->notifiers[$serviceName] = $notifier;
    }

    /**
     * getNotifier
     * 
     * @params string $notifierServiceName
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
        $notification = null;

        if ($data instanceof Notification) {
            $notification = NotificationFactory::createFromObject($type, $data);
        } else {
            $notification = NotificationFactory::createFromArray($type, $data);
        }

        $errorList = $this->getValidator()->validate($notification);

        if (count($errorList) > 0) {
            throw new UnavailableNotificationDataException(print_r($errorList, true));
        }

        return $notification;
    }

    /**
     * processNotifications
     *
     * @return
     */
    public function processNotifications()
    {
        $notifications = $this
            ->getEntityManager()
            ->getRepository('IDCINotificationBundle:Notification')
            ->getNotificationByStatus('NEW')
        ;

        foreach($notifications as $notificationEntity) {
            $type = Inflector::underscore(str_replace('Notification', '', $notificationEntity->getType()));
            $notification = $this->create($type, $notificationEntity);

            $notifier = $this->getNotifier($notification->getNotifierServiceName());
            $notifier->addNotification($notification);
        }

        var_dump(count($this->getNotifiers())); die;
    }
}

