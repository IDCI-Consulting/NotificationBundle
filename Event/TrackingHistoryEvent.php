<?php

namespace IDCI\Bundle\NotificationBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use IDCI\Bundle\NotificationBundle\Entity\TrackingHistory;

/**
 * TrackingHistoryEvent.
 *
 * @author Rémy MENCE <remymence@gmail.com>
 */
class TrackingHistoryEvent extends Event
{
    protected $trackingHistory;

    /**
     * Constructor.
     *
     * @param TrackingHistory $trackingHistory
     */
    public function __construct(TrackingHistory $trackingHistory)
    {
        $this->trackingHistory = $trackingHistory;
    }

    /**
     * Get Object.
     *
     * @return TrackingHistory
     */
    public function getTrackingHistory()
    {
        return $this->trackingHistory;
    }
}
