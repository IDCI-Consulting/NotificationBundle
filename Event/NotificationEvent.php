<?php

namespace IDCI\Bundle\NotificationBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

/**
 * NotificationEvent.
 *
 * @author Gabriel Bondaz <gabriel.bondaz@idci-consulting.fr>
 */
class NotificationEvent extends Event
{
    protected $notification;

    /**
     * Constructor.
     *
     * @param Notification $notification
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get Object.
     *
     * @return Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }
}
