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
    protected $type;
    protected $source;
    protected $createdAt;
    protected $updatedAt;
    protected $status;
    protected $log;

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getsStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function setLog($log)
    {
        $this->log = $log;
    }
    /**
     * @see NotificationInterface
     */
    public function fromNotification(Notification $notificationEntity)
    {
        $this
            ->setFrom($notificationEntity->getFrom())
            ->setType($notificationEntity->getType())
            ->setSource($notificationEntity->getSource())
            ->setCreatedAt($notificationEntity->getCreatedAt())
            ->setUpdatedAt($notificationEntity->getUpdatedAt())
            ->setStatus($notificationEntity->getStatus())
            ->setLog($notificationEntity->getLog())
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
