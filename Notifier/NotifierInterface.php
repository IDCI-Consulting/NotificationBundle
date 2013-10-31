<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;

interface NotifierInterface
{
    /**
     * Send notification
     *
     * @param Notification $notification
     * @return boolean
     */
    public function sendNotification(Notification $notification);

    /**
     * Get To Fields
     *
     * @return array|false
     */
    public function getToFields();

    /**
     * Get Content Fields
     *
     * @return array|false
     */
    public function getContentFields();
}
