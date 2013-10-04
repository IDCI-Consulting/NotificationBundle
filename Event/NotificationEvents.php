<?php

namespace IDCI\Bundle\NotificationBundle\Event;

/**
 * NotificationEvents
 *
 * @author Gabriel Bondaz <gabriel.bondaz@idci-consulting.fr>
 */
final class NotificationEvents
{
    /**
     * @var string
     */
    const PRE_UPDATE =  'idci_notification.notification.pre_update';
    const POST_UPDATE = 'idci_notification.notification.post_update';

    const PRE_DELETE =  'idci_notification.notification.pre_delete';
    const POST_DELETE = 'idci_notification.notification.post_delete';
}