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

abstract class AbstractProxyNotification implements NotificationInterface
{
    protected $notification;

    /**
     * Constructor
     *
     * @param Notification $notification
     * @return Notification
     */
    public function __construct(Notification $notification = null)
    {
        if(is_null($notification)) {
            $this->notification = new Notification();
            $this->notification->setType($this->getTypeName());
        } else {
            $this->notification = $notification;
        }
    }

    /**
     * @see NotificationInterface
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @see NotificationInterface
     */
    public function setNotification(Notification $notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get from
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->getNotification()->getFrom();
    }

    /**
     * Set from
     *
     * @return string
     * @param string $from 
     */
    public function setFrom($from)
    {
        $this->getNotification()->setFrom($from);

        return $this;
    }

    /**
     * Get Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getNotification()->getType();
    }

    /**
     * Set type
     *
     * @return string
     * @param string $type 
     */
    public function setType($type)
    {
        $this->getNotification()->setType($from);

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->getNotification()->getSource();
    }

    /**
     * Set source
     *
     * @return string
     * @param string $source 
     */
    public function setSource($source)
    {
        $this->getNotification()->setSource($source);

        return $this;
    }

    /**
     * Get created at
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->getNotification()->getCreatedAt();
    }

    /**
     * Set created at
     *
     * @return DateTime
     * @param DateTime $createdAt 
     */
    public function setCreatedAt($createdAt)
    {
        $this->getNotification()->setCreatedAt($createdAt);

        return $this;
    }

    /**
     * Get updated at
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->getNotification()->getUpdatedAt();
    }

    /**
     * Set updated at
     *
     * @return DateTime
     * @param DateTime $updatedAt 
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->getNotification()->setUpdatedAt($updatedAt);

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getNotification()->getStatus();
    }

    /**
     * Set status
     *
     * @return string
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->getNotification()->setStatus($status);

        return $this;
    }

    /**
     * Get log
     *
     * @return string
     */
    public function getLog()
    {
        return $this->getNotification()->getLog();
    }

    /**
     * Set log
     *
     * @return string
     * @param string $log
     */
    public function setLog($log)
    {
        $this->getNotification()->setLog($log);

        return $this;
    }
}
