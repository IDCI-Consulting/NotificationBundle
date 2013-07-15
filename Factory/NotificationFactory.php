<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Factory;

use IDCI\Bundle\NotificationBundle\Model\NotificationInterface;
use IDCI\Bundle\NotificationBundle\Exception\UnavailableNotificationParameter;

class NotificationFactory
{
	private function __construct()
    {

    }

    /**
     * Create
     *
     * @param string $type
     * @param array $parameters
     * @return NotificationInterface
     */
    static public function create($type, $parameters)
    {
        $class = sprintf(
            '%s\%sNotification',
            'IDCI\Bundle\NotificationBundle\Model',
            self::camelize($type)
        );

        $notification = new $class();
        $rc = new \ReflectionClass($notification);

        foreach($parameters as $field => $value) {
            $setter = sprintf('set%s', self::camelize($field));
            if (!$rc->hasMethod($setter)) {
                throw new UnavailableNotificationParameter(sprintf(
                    'Unknown field %s for %s object',
                    $field,
                    $class
                ));
            }

            $notification->$setter($value);
        }

        return $notification;
    }

    static public function camelize($word)
    {
        return str_replace(' ', '', ucwords(preg_replace('/[^A-Z^a-z^0-9]+/', ' ', $word)));
    }
}
