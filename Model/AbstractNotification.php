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
    protected $from;

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @see NotificationInterface
     */
    public function fromNotification(Notification $notificationEntity)
    {
        $this
            ->setFrom($notificationEntity->getFrom())
        ;
    }

    /**
     * @see NotificationInterface
     */
    public function toNotification()
    {
        $rc = new \ReflectionClass($this);
        $notification = new Notification();
        $notification
            ->setType($rc->getShortName())
            ->setFrom('no-reply@tessi.fr')
        ;

        return $notification;
    }
}
