<?php

namespace IDCI\Bundle\NotificationBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use IDCI\Bundle\NotificationBundle\Entity\NotifierConfiguration;

/**
 * NotifierConfigurationEvent
 *
 * @author Gabriel Bondaz <gabriel.bondaz@idci-consulting.fr>
 */
class NotifierConfigurationEvent extends Event
{
    protected $notifierConfiguration;

    /**
     * Constructor
     *
     * @param NotifierConfiguration $notifierConfiguration
     */
    public function __construct(NotifierConfiguration $notifierConfiguration)
    {
        $this->notifierConfiguration = $notifierConfiguration;
    }

    /**
     * Get Object
     *
     * @return NotifierConfiguration
     */
    public function getNotifierConfiguration()
    {
        return $this->notifierConfiguration;
    }
}