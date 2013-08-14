<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Exception;

class UnavailableNotificationDataException extends \Exception
{
    /**
     * Constructor
     *
     * @param array $errorList
     */
    public function __construct($errorList)
    {
        parent::__construct(print_r($errorList, true), 0, null);
    }
}

