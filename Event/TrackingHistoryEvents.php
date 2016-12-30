<?php

namespace IDCI\Bundle\NotificationBundle\Event;

/**
 * TrackingHistoryEvents
 *
 * @author Rémy MENCE <remymence@gmail.com>
 */
final class TrackingHistoryEvents
{
    /**
     * @var string
     */
    const PRE_CREATE =  'idci_notification.trackingHistory.pre_create';
    const POST_CREATE = 'idci_notification.trackingHistory.post_create';

    const PRE_DELETE =  'idci_notification.trackingHistory.pre_delete';
    const POST_DELETE = 'idci_notification.trackingHistory.post_delete';
}
