<?php

/**
 *
* @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Factory;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Model\NotificationInterface;
use IDCI\Bundle\NotificationBundle\Exception\UnavailableNotificationParameterException;
use IDCI\Bundle\NotificationBundle\Util\Inflector;

abstract class NotificationFactory
{
    /**
     * Create
     *
     * @param string $type
     * @return NotificationInterface
     */
    static private function create($type)
    {
        $class = sprintf(
            '%s\%sNotification',
            'IDCI\Bundle\NotificationBundle\Model',
            Inflector::camelize($type)
        );

        return new $class();
    }

    /**
     * Create from array
     *
     * @param string $type
     * @param Notification $notification
     * @return NotificationInterface
     */
    static public function createFromObject($type, Notification $notificationEntity)
    {
        $notification = self::create($type);
        $notification->fromNotification($notificationEntity);

        return $notification;
    }

    /**
     * Create from array
     *
     * @param string $type
     * @param array $parameters
     * @return NotificationInterface
     */
    static public function createFromArray($type, $parameters)
    {
        $notification = self::create($type);
        $rc = new \ReflectionClass($notification);

        foreach($parameters as $field => $value) {
            $setter = sprintf('set%s', Inflector::camelize($field));
            if (!$rc->hasMethod($setter)) {
                throw new UnavailableNotificationParameterException(sprintf(
                    'Unknown field %s for %s object',
                    $field,
                    $class
                ));
            }

            $notification->$setter($value);
        }

        return $notification;
    }
}
