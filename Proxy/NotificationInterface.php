<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Proxy;

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
     * Get notification type name
     *
     * @return string
     */
    public function getTypeName();

    /**
     * Get notification
     *
     * @return Notification
     */
    public function getNotification();

    /**
     * Set notification
     *
     * @param Notification $notification
     * @return NotificationInterface
     */
    public function setNotification(Notification $notification);
}
