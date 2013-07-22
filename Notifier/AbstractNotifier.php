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
    protected $processLog = array();

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
     * Reset process log
     *
     * @return array
     */
    public function resetProcessLog()
    {
        $this->processLog = array();
    }

    /**
     * Log proce processed notification
     *
     * @param Notification $notification
     */
    public function logProcessedNotification(Notification $notification)
    {
        if (!isset($this->processLog[$notification->getStatus()])) {
            $this->processLog[$notification->getStatus()] = 0;
        }

        $this->processLog[$notification->getStatus()] += 1;
    }

    /**
     * Get process log
     *
     * @return string
     */
    public function getProcessLog()
    {
        return $this->processLog;
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
        $this->resetProcessLog();

        foreach($this->getProxyNotifications() as $proxyNotification) {
            $notification = $proxyNotification->getNotification();

            try {
                $this->send($proxyNotification);
                $notification->setStatus(Notification::STATUS_DONE);
            } catch (\Exception $e) {
                $notification->setStatus(Notification::STATUS_ERROR);
                $notification->setLog($e->getMessage());
            }

            $this->logProcessedNotification($notification);
            $this->getEntityManager()->persist($notification);
        }

        $this->getEntityManager()->flush();
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
