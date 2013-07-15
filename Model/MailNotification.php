<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Sekou KOÏTA <sekou.koita@supinfo.com>
 * @license: GPL
 *
 */

namespace IDCI\Bundle\NotificationBundle\Model;


class MailNotification implements NotificationInterface
{    
    protected $firstName;
    protected $lastName;
    protected $addresstName;
    protected $postalCode;
    protected $city;
    protected $country;
    protected $message;

    public function convertToNotification()
    {
    }

}
