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

interface NotifierInterface
{
    /**
     * Add proxy notification
     *
     * @param NotificationInterface $proxyNotification
     */
    public function addProxyNotification(NotificationInterface $proxyNotification);

    /**
     * Send notifications
     */
    public function process();
}
