<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @author:  Pichet PUTH <pichet.puth@utt.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Notifier;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\NotificationBundle\Entity\Notification;

interface NotifierInterface
{
    /**
     * Send notification
     *
     * @param Notification $notification
     *
     * @return boolean
     */
    public function sendNotification(Notification $notification);

    /**
     * Get configuration
     *
     * @param Notification $notification
     *
     * @return array
     */
    public function getConfiguration(Notification $notification);

    /**
     * Get To Fields
     *
     * @return array|false
     */
    public function getToFields();

    /**
     * Get Content Fields
     *
     * @return array|false
     */
    public function getContentFields();

    /**
     * Get From Fields
     *
     * @return array|false
     */
    public function getFromFields();

    /**
     * Clean data
     *
     * @param array $data The data to be checked
     *
     * @return array The cleaned data
     */
    public function cleanData($data);

    /**
     * Get resolver
     *
     * @param array $options
     *
     * @return $resolver
     */
    public function getResolver(array $options);

    /**
     * Get field option
     *
     * @param string $field The name of field("to", "from", "content")
     *
     * @return array
     */
    public function getFieldOptions($field);

    /**
     * Configure OptionResolver with default options
     *
     * @param OptionsResolver $resolver
     * @param array           $fieldOptions
     */
    public function setDefaultOptions(OptionsResolver $resolver, array $fieldOptions);

}