<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Model\NotificationInterface;

abstract class AbstractNotifier implements NotifierInterface
{
    protected $notifications = array();

    /**
     * @see NotifierInterface
     */
    public function addNotification(NotificationInterface $notification)
    {
        $this->notifications[] = $notification;
    }

    /**
     * @see NotifierInterface
     */
    public function process()
    {
        foreach($this->getNotifications() as $notification) {
            try {
                $this->send($notification);
            } catch (\Exception $e) {
                // TODO
            }
            // TODO: Change status to OK
            $notification->setStatus($STATUS_DONE);
        }
    }

    /**
     * Get notifications
     *
     * @return notifications
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Send notifications
     *
     * @param NotificationInterface $notification
     */
    abstract public function send(NotificationInterface $notification);
}
