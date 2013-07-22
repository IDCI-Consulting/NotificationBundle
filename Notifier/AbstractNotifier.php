<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Proxy\NotificationInterface;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

abstract class AbstractNotifier implements NotifierInterface
{
    protected $proxyNotifications = array();
    protected $entityManager;

    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get Entity Manager
     *
     * @return Doctrine\ORM\EntityManagers
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @see NotifierInterface
     */
    public function addProxyNotification(NotificationInterface $proxyNotification)
    {
        $this->proxyNotifications[] = $proxyNotification;
    }

    /**
     * @see NotifierInterface
     */
    public function process()
    {
        foreach($this->getProxyNotifications() as $proxyNotification) {
            try {
                $this->send($proxyNotification);
            } catch (\Exception $e) {
                // TODO
            }
            $notification = $proxyNotification->getNotification();
            $notification->setStatus(Notification::STATUS_DONE);
            $this->getEntityManager()->persist($notification);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Get notifications
     *
     * @return array
     */
    public function getProxyNotifications()
    {
        return $this->proxyNotifications;
    }

    /**
     * Send notifications
     *
     * @param NotificationInterface $proxyNotification
     */
    abstract public function send(NotificationInterface $proxyNotification);
}
