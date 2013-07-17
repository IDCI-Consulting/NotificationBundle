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

interface NotifierInterface
{
    /**
     * Add notification
     *
     * @param Notification $notification
     */
    public function addNotification(NotificationInterface $notification);

    /**
     * Send notifications
     */
    public function process();
}
