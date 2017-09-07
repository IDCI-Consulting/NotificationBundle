<?php

namespace IDCI\Bundle\NotificationBundle\Event;

/**
 * NotifierConfigurationEvents.
 *
 * @author Gabriel Bondaz <gabriel.bondaz@idci-consulting.fr>
 */
final class NotifierConfigurationEvents
{
    /**
     * @var string
     */
    const PRE_CREATE = 'idci_notification.notifier_configuration.pre_create';
    const POST_CREATE = 'idci_notification.notifier_configuration.post_create';

    const PRE_UPDATE = 'idci_notification.notifier_configuration.pre_update';
    const POST_UPDATE = 'idci_notification.notifier_configuration.post_update';

    const PRE_DELETE = 'idci_notification.notifier_configuration.pre_delete';
    const POST_DELETE = 'idci_notification.notifier_configuration.post_delete';
}
