<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Service;

use IDCI\Bundle\NotificationBundle\Entity\Notification;
use IDCI\Bundle\NotificationBundle\Exception\UnavailableNotificationData;
use IDCI\Bundle\NotificationBundle\Factory\NotificationFactory;

class Manager
{
    protected $validator;

    /**
     * Constructor
     */
    public function __construct($validator)
    {
        $this->validator = $validator;
    }

    /**
     * getValidator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * isValidNotificationData
     */
    public function checkNotificationData($type, $data)
    {
        $notification = NotificationFactory::create($type, $data);
        $errorList = $this->getValidator()->validate($notification);

        if (count($errorList) > 0) {
            throw new UnavailableNotificationData(print_r($errorList, true));
        }

        return true;
    }

    /**
     * createFromArray
     *
     * @return NotificationInterface
     */
    public function createFromArray($type, $data)
    {
        $this->checkNotificationData($type, $data);

        return NotificationFactory::create($type, $data);
    }
}

