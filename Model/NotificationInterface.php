<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Model;

use IDCI\Bundle\NotificationBundle\Entity\Notification;

interface NotificationInterface
{
    /**
     * Get notifier service name
     *
     * @return string
     */
    public function getNotifierServiceName();

    /**
     * Convert to a notification object
     *
     * @return Notification
     */
    public function toNotification();

    /**
     * Import data from a notification object
     *
     * @param Notification $notification
     */
    public function fromNotification(Notification $notificationEntity);
}
