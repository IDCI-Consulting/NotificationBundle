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

abstract class AbstractNotification implements NotificationInterface
{
    /**
     * Convert to Notification
     *
     * @return Notification
     */
    public function convertToNotification()
    {
        $rc = new \ReflectionClass($this);
        $notification = new Notification();
        $notification
            ->setType($rc->getShortName())
            ->setFrom('tessi')
        ;

        return $notification;
    }
}
