<?php

/**
 * 
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Model;

use IDCI\Bundle\NotificationBundle\Entity\NotificationEntity;


class MailNotification extends NotificationEntity
{    
    protected $firstName;
    protected $lastName;
    protected $addresstName;
    protected $postalCode;
    protected $city;
    protected $country;
    protected $message;

}
