<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class UnavailableNotificationParameterException extends \Exception
{
    /**
     * Constructor
     *
     * @param string $className
     * @param string $field
     */
    public function __construct($className, $field)
    {
        parent::__construct(
            sprintf(
                'Unknown field %s for %s object',
                $field,
                $className
            ),
            0,
            null
        );
    }
}

