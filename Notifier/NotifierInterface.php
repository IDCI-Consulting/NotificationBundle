<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use IDCI\Bundle\NotificationBundle\Entity\Notification;

interface NotifierInterface
{
    /**
     * Send notification.
     *
     * @param Notification $notification
     *
     * @return bool
     */
    public function sendNotification(Notification $notification);

    /**
     * Get configuration.
     *
     * @param Notification $notification
     *
     * @return array
     */
    public function getConfiguration(Notification $notification);

    /**
     * Get To Fields.
     *
     * @return array|false
     */
    public function getToFields();

    /**
     * Get Content Fields.
     *
     * @return array|false
     */
    public function getContentFields();

    /**
     * Get From Fields.
     *
     * @return array|false
     */
    public function getFromFields();

    /**
     * Clean data.
     *
     * @param array $data The data to be checked
     *
     * @return array The cleaned data
     */
    public function cleanData($data);

    /**
     * Clean empty value.
     *
     * @param array $data The data to be cleaned
     *
     * @return array $data The cleaned data
     */
    public function cleanEmptyValue($data);
}
