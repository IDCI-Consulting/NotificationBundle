<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Factory;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Proxy\NotificationInterface;
use IDCI\Bundle\NotificationBundle\Exception\UnavailableNotificationParameterException;
use IDCI\Bundle\NotificationBundle\Util\Inflector;

abstract class NotificationFactory
{
    /**
     * Create Proxy from array
     *
     * @param string $type
     * @param Notification $notification
     * @return NotificationInterface
     */
    static public function createProxyFromObject($type, Notification $notification)
    {
        $proxyNotification = self::createProxy($type);
        $proxyNotification->setNotification($notification);

        return $proxyNotification;
    }

    /**
     * Create Proxy from array
     *
     * @param string $type
     * @param array $parameters
     * @return NotificationInterface
     */
    static public function createProxyFromArray($type, $parameters)
    {
        $proxyNotification = self::createProxy($type);
        $rc = new \ReflectionClass($proxyNotification);

        foreach($parameters as $field => $value) {
            $setter = sprintf('set%s', Inflector::camelize($field));
            if (!$rc->hasMethod($setter)) {
                throw new UnavailableNotificationParameterException(sprintf(
                    'Unknown field %s for %s object',
                    $field,
                    $class
                ));
            }

            $proxyNotification->$setter($value);
        }

        return $proxyNotification;
    }

    /**
     * Create Proxy
     *
     * @param string $type
     * @return NotificationInterface
     */
    static private function createProxy($type)
    {
        $class = sprintf(
            '%s\%sProxyNotification',
            'IDCI\Bundle\NotificationBundle\Proxy',
            Inflector::camelize($type)
        );

        return new $class();
    }
}
